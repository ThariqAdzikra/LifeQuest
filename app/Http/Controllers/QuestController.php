<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\QuestLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuestController extends Controller
{
    /**
     * Menampilkan semua daftar quest (Quest Board).
     */
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // 1. Quest Saya (Hanya yang sedang aktif)
        $myQuests = QuestLog::where('user_id', $userId)
                            ->where('status', 'active')
                            ->with('quest')
                            ->latest('updated_at')
                            ->get();

        // 2. Riwayat (Hanya yang sudah selesai)
        $completedQuests = QuestLog::where('user_id', $userId)
                                   ->where('status', 'completed')
                                   ->with('quest')
                                   ->latest('updated_at')
                                   ->get();


        // 3. Quest Tersedia (Admin)
        $allAvailableAdminQuests = Quest::where('is_admin_quest', true)
            ->with('logs')
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'active');
            })
            ->get();

        $adminQuests = $allAvailableAdminQuests->filter(function ($quest) use ($userId, $now) {
            return $this->isQuestAvailable($quest, $userId, $now);
        });


        // 4. Quest Tersedia (Komunitas / Buatan User)
        $allAvailableUserQuests = Quest::where('is_admin_quest', false)
            ->with(['logs', 'creator'])
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'active');
            })
            ->get();

        $userQuests = $allAvailableUserQuests->filter(function ($quest) use ($userId, $now) {
            return $this->isQuestAvailable($quest, $userId, $now);
        });

        return view('quests.index', compact(
            'myQuests',
            'adminQuests',
            'userQuests',
            'completedQuests'
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

        if (!$lastCompletedLog) return true;
        if ($quest->frequency == 'once') return false;
        if ($quest->frequency == 'daily') return $lastCompletionTime->isBefore($now->startOfDay());
        if ($quest->frequency == 'weekly') return $lastCompletionTime->isBefore($now->startOfWeek());

        return false;
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
        ]);

        return redirect()->route('quests.index')->with('success', 'Quest kustom berhasil dibuat! Cek di tab "Quest Tersedia".');
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
                               ->where('status', 'active')
                               ->first();

        if ($existingLog) {
            return redirect()->route('quests.index')->with('error', 'Anda sudah mengambil quest ini!');
        }

        QuestLog::create([
            'user_id'  => $userId,
            'quest_id' => $quest->id,
            'status'   => 'active',
            'date'     => Carbon::now() // Memperbaiki error 'date'
        ]);

        return redirect()->route('quests.index')->with('success', 'Quest berhasil diambil!');
    }

    /**
     * Menyelesaikan quest (tombol 'Selesaikan' di 'Quest Saya').
     */
    public function complete(Request $request, $logId)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        
        if ($log->status == 'completed') {
            return redirect()->route('quests.index')->with('error', 'Quest ini sudah diselesaikan.');
        }

        $quest = $log->quest;
        $user = Auth::user();

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

        return redirect()->route('quests.index')->with('success', 'Quest ' . $quest->title . ' selesai! Reward didapat!');
    }

    /**
     * Membatalkan quest (tombol 'Batalkan' di 'Quest Saya').
     */
    public function cancel(Request $request, $logId)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        $log->delete();
        return redirect()->route('quests.index')->with('success', 'Quest dibatalkan.');
    }

    
    // --- FUNGSI BARU UNTUK HAPUS QUEST ---
    /**
     * Menghapus quest kustom buatan user.
     * Ditambahkan untuk menangani route: DELETE /quests/{quest}
     */
    public function destroy(Quest $quest)
    {
        // 1. Otorisasi: Pastikan hanya pembuat quest yang bisa menghapus
        if (Auth::id() !== $quest->creator_id) {
            // Jika bukan, tolak
            return redirect()->route('quests.index')->with('error', 'Anda tidak berhak menghapus quest ini!');
        }

        // 2. Keamanan: Pastikan quest admin tidak terhapus (sebagai jaga-jaga)
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin tidak dapat dihapus.');
        }

        // 3. Hapus semua log terkait (supaya tidak ada 'orphan rows')
        // Ini adalah praktik yang baik jika 'onDelete('cascade')' tidak di-set di migrasi.
        $quest->logs()->delete();

        // 4. Hapus quest
        $quest->delete();

        return redirect()->route('quests.index')->with('success', 'Quest kustom Anda berhasil dihapus.');
    }
    // --- AKHIR FUNGSI BARU ---


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