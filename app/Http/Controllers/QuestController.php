<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\QuestLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\AchievementService;
use Illuminate\Support\Facades\Log;
// [TAMBAHAN] Import Paginator
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage; // <-- [TAMBAHAN BARU] Import Storage

class QuestController extends Controller
{
    /**
     * Menampilkan semua daftar quest (Quest Board).
     */
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $perPage = 5; // Tentukan jumlah item per halaman di sini

        // 1. Quest Saya (Aktif) - [PERBAIKAN] Menggunakan paginate()
        $myQuests = QuestLog::where('user_id', $userId)
                            // [MODIFIKASI] Tampilkan juga yg 'pending_review' di tab "Quest Saya"
                            ->whereIn('status', ['active', 'pending_review']) 
                            ->with('quest')
                            ->latest('updated_at')
                            ->paginate($perPage, ['*'], 'myQuests_page'); // Nama page unik

        // 2. Riwayat (Selesai) - [PENYESUAIAN] Mengganti nama page
        $completedQuests = QuestLog::where('user_id', $userId)
                                   ->where('status', 'completed')
                                   ->with('quest')
                                   ->latest('updated_at')
                                   ->paginate($perPage, ['*'], 'completed_page'); // Ganti nama page


        // 3. Quest Tersedia (Admin) - [PERBAIKAN] Pagination Manual
        $allAvailableAdminQuests = Quest::where('is_admin_quest', true)
            ->with('logs')
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                // Jangan tampilkan jika statusnya 'active' ATAU 'pending_review'
                $query->where('user_id', $userId)->whereIn('status', ['active', 'pending_review']);
            })
            ->get(); // Tetap get() di sini

        $filteredAdminQuests = $allAvailableAdminQuests->filter(function ($quest) use ($userId, $now) {
            $quest->created_at = Carbon::parse($quest->created_at);
            return $this->isQuestAvailable($quest, $userId, $now);
        });
        
        // Logika Pagination Manual untuk Admin Quests
        $adminQuests_page = Paginator::resolveCurrentPage('adminQuests_page');
        $adminQuests = new LengthAwarePaginator(
            $filteredAdminQuests->forPage($adminQuests_page, $perPage), // Item untuk halaman ini
            $filteredAdminQuests->count(), // Total item
            $perPage, // Item per halaman
            $adminQuests_page, // Halaman saat ini
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'adminQuests_page'] // Opsi
        );


        // 4. Quest Tersedia (Pribadi / Buatan User Sendiri) - [PERBAIKAN] Pagination Manual
        $allAvailablePersonalQuests = Quest::where('is_admin_quest', false)
            ->where('creator_id', $userId)
            ->where('is_active', true)
            ->with(['logs', 'creator'])
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'active');
            })
            ->get(); // Tetap get() di sini

        $filteredPersonalQuests = $allAvailablePersonalQuests->filter(function ($quest) use ($userId, $now) {
            $quest->created_at = Carbon::parse($quest->created_at);
            return $this->isQuestAvailable($quest, $userId, $now);
        });

        // Logika Pagination Manual untuk Personal Quests
        $personalQuests_page = Paginator::resolveCurrentPage('personalQuests_page');
        $personalQuests = new LengthAwarePaginator(
            $filteredPersonalQuests->forPage($personalQuests_page, $perPage), // Item untuk halaman ini
            $filteredPersonalQuests->count(), // Total item
            $perPage, // Item per halaman
            $personalQuests_page, // Halaman saat ini
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'personalQuests_page'] // Opsi
        );

        // 5. Quest Pribadi untuk Kelola - DENGAN PAGINATION (Sudah Benar)
        // HANYA yang Harian atau Mingguan
        $myPersonalQuests = Quest::where('creator_id', $userId)
                                ->where('is_admin_quest', false)
                                ->whereIn('frequency', ['daily', 'weekly'])
                                ->latest('created_at')
                                ->paginate($perPage, ['*'], 'manage_page'); // Nama page ini sudah benar


        return view('quests.index', compact(
            'myQuests',
            'adminQuests',
            'personalQuests',
            'completedQuests',
            'myPersonalQuests'
        ));
    }

    /**
     * Helper function untuk mengecek apakah quest tersedia berdasarkan cooldown.
     */
    private function isQuestAvailable($quest, $userId, $now)
    {
        $lastCompletedLog = $quest->logs
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->sortByDesc('updated_at')
            ->first();

        if (!$lastCompletedLog) {
            // Belum pernah diselesaikan
            if ($quest->frequency == 'weekly') {
                // Jika mingguan, hanya tampil di hari yang sesuai
                return $quest->created_at->dayOfWeek == $now->dayOfWeek;
            }
            // Jika 'once' atau 'daily', pasti tersedia
            return true;
        }

        // Sudah pernah diselesaikan
        $lastCompletionTime = $lastCompletedLog->updated_at;

        if ($quest->frequency == 'once') {
            return false; // Sekali jalan, sudah selesai, tidak akan muncul lagi
        }
        
        if ($quest->frequency == 'daily') {
            // Harian: Cek apakah terakhir selesai < awal hari ini
            return $lastCompletionTime->isBefore($now->startOfDay());
        }

        if ($quest->frequency == 'weekly') {
            // Mingguan:
            // 1. Cek apakah hari ini adalah hari yang benar
            $isCorrectDay = ($quest->created_at->dayOfWeek == $now->dayOfWeek);
            // 2. Cek apakah terakhir selesai < awal hari ini (mencegah selesai >1x sehari)
            $isNotCompletedToday = $lastCompletionTime->isBefore($now->startOfDay());

            return $isCorrectDay && $isNotCompletedToday;
        }

        return false; // Default
    }


    /**
     * Menyimpan quest baru (dari tab 'Buat Quest').
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'frequency' => 'required|in:once,daily,weekly',
            'stat_reward_type' => 'nullable|in:intelligence,strength,stamina,agility',
        ]);

        $rewards = $this->calculateRewards($request->difficulty, $request->stat_reward_type);

        Quest::create([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'frequency' => $request->frequency,
            'exp_reward' => $rewards['exp'],
            'gold_reward' => $rewards['gold'],
            'stat_reward_type' => $request->stat_reward_type,
            'stat_reward_value' => $rewards['stat'],
            'creator_id' => Auth::id(),
            'is_admin_quest' => false,
            'is_active' => true,
        ]);

        // Arahkan ke tab #createQuest
        return redirect(route('quests.index') . '#createQuest')->with('success', 'Quest kustom berhasil dibuat!');
    }

    /**
     * Mengambil quest (tombol 'Ambil Quest').
     */
    public function take(Request $request, $questId)
    {
        $quest = Quest::findOrFail($questId);
        $userId = Auth::id();

        $existingLog = QuestLog::where('user_id', $userId)
                               ->where('quest_id', $questId)
                               ->whereIn('status', ['active', 'pending_review']) // Cek juga yg pending
                               ->first();

        if ($existingLog) {
            return redirect()->route('quests.index')->with('error', 'Anda sudah mengambil quest ini!');
        }
        
        if (!$quest->is_admin_quest && $quest->creator_id !== $userId) {
             return redirect()->route('quests.index')->with('error', 'Anda tidak dapat mengambil quest ini.');
        }

        if (!$quest->is_admin_quest && !$quest->is_active) {
            return redirect()->route('quests.index')->with('error', 'Quest ini sedang Anda jeda.');
        }

        QuestLog::create([
            'user_id'  => $userId,
            'quest_id' => $quest->id,
            'status'   => 'active',
            'date'     => Carbon::now()
        ]);

        return redirect()->route('quests.index')->with('success', 'Quest berhasil diambil!');
    }

    /**
     * Menyelesaikan quest NON-ADMIN (tombol 'Selesaikan' di 'Quest Saya').
     */
    public function complete(Request $request, $logId, AchievementService $achievementService)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        
        if ($log->status == 'completed') {
            return redirect()->route('quests.index')->with('error', 'Quest ini sudah diselesaikan.');
        }

        $quest = $log->quest;
        $user = Auth::user();
        
        // [PERUBAHAN] Tambahkan guard: Fungsi ini HANYA untuk quest NON-ADMIN
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin harus dikirim (submit) dengan bukti, bukan diselesaikan.');
        }

        // 1. Berikan Reward ke User
        $user->exp += $quest->exp_reward;
        $user->gold += $quest->gold_reward;

        if ($quest->stat_reward_type && $quest->stat_reward_value > 0) {
            $stat = $quest->stat_reward_type;
            $user->{$stat} += $quest->stat_reward_value;
        }
        $user->save();

        // 2. Ubah status log menjadi 'completed'
        $log->status = 'completed';
        $log->updated_at = Carbon::now(); 
        $log->save();

        // 3. [DIHAPUS] Pengecekan AchievementService dipindahkan ke Admin (SubmissionController)
        // Blok if ($quest->is_admin_quest) { ... } dihapus

        return redirect()->route('quests.index')->with('success', 'Quest ' . $quest->title . ' selesai! Reward didapat!');
    }

    // ====================================================================
    // [FUNGSI BARU] Mengirim submission untuk quest admin
    // ====================================================================
    public function submit(Request $request, $logId)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:jpg,jpeg,png,pdf,zip|max:5120', // Maks 5MB
            'submission_notes' => 'nullable|string|max:1000',
        ]);

        $log = QuestLog::where('id', $logId)
                       ->where('user_id', Auth::id())
                       ->where('status', 'active') // Hanya yg statusnya 'active'
                       ->firstOrFail();

        $quest = $log->quest;

        // Guard: Pastikan ini adalah quest admin
        if (!$quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest ini tidak memerlukan submission.');
        }

        // 1. Simpan file
        // 'submissions' adalah folder di dlm 'storage/app/public'
        $filePath = $request->file('submission_file')->store('submissions', 'public');

        // 2. Update log
        $log->submission_file_path = $filePath;
        $log->submission_notes = $request->submission_notes;
        $log->status = 'pending_review'; // <-- STATUS BARU!
        $log->updated_at = Carbon::now(); // Update timestamp
        $log->save();
        
        // 3. JANGAN berikan reward dulu!

        return redirect()->route('quests.index')->with('success', 'Quest berhasil dikirim untuk review!');
    }


    /**
     * Membatalkan quest (tombol 'Batalkan' di 'Quest Saya').
     */
    public function cancel(Request $request, $logId)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        
        // [TAMBAHAN] Hapus file jika ada saat membatalkan
        if ($log->status == 'pending_review' && $log->submission_file_path) {
             Storage::disk('public')->delete($log->submission_file_path);
        }
        
        $log->delete();
        return redirect()->route('quests.index')->with('success', 'Quest dibatalkan.');
    }

    
    /**
     * Menghapus quest kustom buatan user.
     */
    public function destroy(Quest $quest)
    {
        // 1. Otorisasi: Pastikan hanya pembuat quest yang bisa menghapus
        if (Auth::id() !== $quest->creator_id) {
            return redirect()->route('quests.index')->with('error', 'Anda tidak berhak menghapus quest ini!');
        }

        // 2. Keamanan: Pastikan quest admin tidak terhapus
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin tidak dapat dihapus.');
        }

        // 3. Hapus semua log terkait
        $quest->logs()->delete();

        // 4. Hapus quest
        $quest->delete();

        // Arahkan kembali ke tab #createQuest
        return redirect(route('quests.index') . '#createQuest')->with('success', 'Quest kustom Anda berhasil dihapus.');
    }

    /**
     * Menghentikan atau mengaktifkan kembali quest harian/mingguan.
     */
    public function toggleStatus(Quest $quest)
    {
        // 1. Otorisasi: Hanya pembuat
        if (Auth::id() !== $quest->creator_id) {
            return redirect()->route('quests.index')->with('error', 'Akses ditolak.');
        }

        // 2. Validasi: Hanya quest pribadi
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin tidak dapat diubah.');
        }

        // 3. Validasi: Hanya quest harian atau mingguan
        if (!in_array($quest->frequency, ['daily', 'weekly'])) {
            return redirect(route('quests.index') . '#createQuest')->with('error', 'Hanya quest Harian atau Mingguan yang bisa dijeda.');
        }

        // 4. Toggle status
        $quest->is_active = !$quest->is_active;
        $quest->save();

        $message = $quest->is_active ? 'Quest berhasil diaktifkan kembali.' : 'Quest berhasil dijeda.';
        // Redirect kembali ke tab 'createQuest'
        return redirect(route('quests.index') . '#createQuest')->with('success', $message);
    }


    /**
     * Helper function untuk menghitung reward otomatis.
     */
    private function calculateRewards($difficulty, $statType)
    {
        $rewards = ['exp' => 0, 'gold' => 0, 'stat' => 0];
        switch ($difficulty) {
            case 'easy': $rewards = ['exp' => 10, 'gold' => 5, 'stat' => $statType ? 1 : 0]; break;
            case 'medium': $rewards = ['exp' => 25, 'gold' => 15, 'stat' => $statType ? 3 : 0]; break;
            case 'hard': $rewards = ['exp' => 50, 'gold' => 30, 'stat' => $statType ? 5 : 0]; break;
        }
        return $rewards;
    }
}