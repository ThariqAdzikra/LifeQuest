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
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage; 

class QuestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $perPage = 5; 

        // =====================================================================
        // 1. Quest Saya (Aktif, Pending, Ditolak)
        // =====================================================================
        $myQuests = QuestLog::where('user_id', $userId)
                            ->whereIn('status', ['active', 'pending_review', 'rejected']) 
                            ->with('quest')
                            ->latest('updated_at') // [PENTING] Agar yang baru diambil/diupdate ada di atas
                            ->paginate($perPage, ['*'], 'myQuests_page'); 

        // =====================================================================
        // 2. Riwayat (Selesai)
        // =====================================================================
        $completedQuests = QuestLog::where('user_id', $userId)
                                   ->where('status', 'completed')
                                   ->with('quest')
                                   ->latest('updated_at') // [PENTING] Yang baru selesai di atas
                                   ->paginate($perPage, ['*'], 'completed_page');


        // =====================================================================
        // 3. Quest Tersedia (Admin)
        // =====================================================================
        $allAvailableAdminQuests = Quest::where('is_admin_quest', true)
            ->with('logs')
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                // Jangan tampilkan jika sedang dikerjakan
                $query->where('user_id', $userId)->whereIn('status', ['active', 'pending_review', 'rejected']);
            })
            ->latest() // Sort awal dari DB
            ->get(); 

        // Filter logika waktu (Daily/Weekly)
        $filteredAdminQuests = $allAvailableAdminQuests->filter(function ($quest) use ($userId, $now) {
            $quest->created_at = Carbon::parse($quest->created_at);
            return $this->isQuestAvailable($quest, $userId, $now);
        });

        // [PENTING] Sortir koleksi hasil filter agar yang terbaru tetap di atas
        $filteredAdminQuests = $filteredAdminQuests->sortByDesc('created_at');
        
        // Pagination Manual untuk Collection
        $adminQuests_page = Paginator::resolveCurrentPage('adminQuests_page');
        $adminQuests = new LengthAwarePaginator(
            $filteredAdminQuests->forPage($adminQuests_page, $perPage),
            $filteredAdminQuests->count(), 
            $perPage, 
            $adminQuests_page,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'adminQuests_page']
        );


        // =====================================================================
        // 4. Quest Tersedia (Pribadi / Buatan User Sendiri)
        // =====================================================================
        $allAvailablePersonalQuests = Quest::where('is_admin_quest', false)
            ->where('creator_id', $userId)
            ->where('is_active', true)
            ->with(['logs', 'creator'])
            ->whereDoesntHave('logs', function ($query) use ($userId) {
                $query->where('user_id', $userId)->whereIn('status', ['active', 'rejected']);
            })
            ->latest() // Sort awal dari DB
            ->get(); 

        $filteredPersonalQuests = $allAvailablePersonalQuests->filter(function ($quest) use ($userId, $now) {
            $quest->created_at = Carbon::parse($quest->created_at);
            return $this->isQuestAvailable($quest, $userId, $now);
        });

        // [PENTING] Sortir koleksi hasil filter
        $filteredPersonalQuests = $filteredPersonalQuests->sortByDesc('created_at');

        $personalQuests_page = Paginator::resolveCurrentPage('personalQuests_page');
        $personalQuests = new LengthAwarePaginator(
            $filteredPersonalQuests->forPage($personalQuests_page, $perPage), 
            $filteredPersonalQuests->count(), 
            $perPage,
            $personalQuests_page, 
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'personalQuests_page']
        );

        // =====================================================================
        // 5. Quest Pribadi untuk Kelola
        // =====================================================================
        $myPersonalQuests = Quest::where('creator_id', $userId)
                                ->where('is_admin_quest', false)
                                ->whereIn('frequency', ['daily', 'weekly'])
                                ->latest('created_at') // [PENTING] Template baru di atas
                                ->paginate($perPage, ['*'], 'manage_page'); 


        return view('quests.index', compact(
            'myQuests',
            'adminQuests',
            'personalQuests',
            'completedQuests',
            'myPersonalQuests'
        ));
    }

    /**
     * Logika Cek Ketersediaan (Daily/Weekly/Once)
     */
    private function isQuestAvailable($quest, $userId, $now)
    {
        $lastCompletedLog = $quest->logs
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->sortByDesc('updated_at')
            ->first();

        if (!$lastCompletedLog) {
            // Jika weekly, pastikan hari ini sesuai jadwal
            if ($quest->frequency == 'weekly') {
                return $quest->created_at->dayOfWeek == $now->dayOfWeek;
            }
            return true;
        }

        $lastCompletionTime = $lastCompletedLog->updated_at;

        if ($quest->frequency == 'once') {
            return false;
        }
        
        if ($quest->frequency == 'daily') {
            return $lastCompletionTime->isBefore($now->startOfDay());
        }

        if ($quest->frequency == 'weekly') {
            $isCorrectDay = ($quest->created_at->dayOfWeek == $now->dayOfWeek);
            $isNotCompletedToday = $lastCompletionTime->isBefore($now->startOfDay());
            return $isCorrectDay && $isNotCompletedToday;
        }
        return false;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'frequency' => 'required|in:once,daily,weekly',
            'stat_reward_type' => 'nullable|in:intelligence,strength,stamina,agility',
            // Validasi input manual jika ada
            'exp_reward' => 'nullable|integer|min:0',
            'gold_reward' => 'nullable|integer|min:0',
            'stat_reward_value' => 'nullable|integer|min:0',
        ]);

        // Hitung default reward
        $defaultRewards = $this->calculateRewards($request->difficulty, $request->stat_reward_type);

        // Prioritaskan input user jika ada, jika tidak pakai default
        $exp = $request->filled('exp_reward') ? $request->exp_reward : $defaultRewards['exp'];
        $gold = $request->filled('gold_reward') ? $request->gold_reward : $defaultRewards['gold'];
        $statVal = $request->filled('stat_reward_value') ? $request->stat_reward_value : $defaultRewards['stat'];

        Quest::create([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'frequency' => $request->frequency,
            'exp_reward' => $exp,
            'gold_reward' => $gold,
            'stat_reward_type' => $request->stat_reward_type,
            'stat_reward_value' => $statVal,
            'creator_id' => Auth::id(),
            'is_admin_quest' => false,
            'is_active' => true,
        ]);

        return redirect(route('quests.index') . '#createQuest')->with('success', 'Quest kustom berhasil dibuat!');
    }

    public function take(Request $request, $questId)
    {
        $quest = Quest::findOrFail($questId);
        $userId = Auth::id();

        $existingLog = QuestLog::where('user_id', $userId)
                               ->where('quest_id', $questId)
                               ->whereIn('status', ['active', 'pending_review', 'rejected']) 
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

    public function complete(Request $request, $logId, AchievementService $achievementService)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        
        if ($log->status == 'completed') {
            return redirect()->route('quests.index')->with('error', 'Quest ini sudah diselesaikan.');
        }

        $quest = $log->quest;
        $user = Auth::user();
        
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin harus dikirim (submit) dengan bukti, bukan diselesaikan.');
        }

        $user->exp += $quest->exp_reward;
        $user->gold += $quest->gold_reward;
        if ($quest->stat_reward_type && $quest->stat_reward_value > 0) {
            $stat = $quest->stat_reward_type;
            $user->{$stat} += $quest->stat_reward_value;
        }
        $user->save();
        
        $log->status = 'completed';
        $log->updated_at = Carbon::now(); // Update timestamp agar muncul paling atas di riwayat
        $log->save();

        return redirect()->route('quests.index')->with('success', 'Quest ' . $quest->title . ' selesai! Reward didapat!');
    }

    public function submit(Request $request, $logId)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:jpg,jpeg,png,pdf,zip|max:5120',
            'submission_notes' => 'nullable|string|max:1000',
        ]);

        $log = QuestLog::where('id', $logId)
                       ->where('user_id', Auth::id())
                       ->whereIn('status', ['active', 'rejected']) 
                       ->firstOrFail();

        $quest = $log->quest;

        if (!$quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest ini tidak memerlukan submission.');
        }

        // Hapus file lama jika ini adalah 'resubmit' dari status 'rejected'
        if ($log->submission_file_path) {
             Storage::disk('public')->delete($log->submission_file_path);
        }

        // Simpan file baru
        $filePath = $request->file('submission_file')->store('submissions', 'public');

        // Update log
        $log->submission_file_path = $filePath;
        $log->submission_notes = $request->submission_notes;
        $log->status = 'pending_review';
        $log->updated_at = Carbon::now(); // Update timestamp
        $log->save();
        
        return redirect()->route('quests.index')->with('success', 'Quest berhasil dikirim untuk review!');
    }

    public function cancel(Request $request, $logId)
    {
        $log = QuestLog::where('id', $logId)->where('user_id', Auth::id())->firstOrFail();
        
        // Hapus file jika ada saat membatalkan
        if (in_array($log->status, ['pending_review', 'rejected']) && $log->submission_file_path) {
             Storage::disk('public')->delete($log->submission_file_path);
        }
        
        $log->delete();
        return redirect()->route('quests.index')->with('success', 'Quest dibatalkan.');
    }

    public function destroy(Quest $quest)
    {
        if (Auth::id() !== $quest->creator_id) {
            return redirect()->route('quests.index')->with('error', 'Anda tidak berhak menghapus quest ini!');
        }
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin tidak dapat dihapus.');
        }
        $quest->logs()->delete();
        $quest->delete();
        return redirect(route('quests.index') . '#createQuest')->with('success', 'Quest kustom Anda berhasil dihapus.');
    }

    public function toggleStatus(Quest $quest)
    {
        if (Auth::id() !== $quest->creator_id) {
            return redirect()->route('quests.index')->with('error', 'Akses ditolak.');
        }
        if ($quest->is_admin_quest) {
            return redirect()->route('quests.index')->with('error', 'Quest admin tidak dapat diubah.');
        }
        if (!in_array($quest->frequency, ['daily', 'weekly'])) {
            return redirect(route('quests.index') . '#createQuest')->with('error', 'Hanya quest Harian atau Mingguan yang bisa dijeda.');
        }
        $quest->is_active = !$quest->is_active;
        $quest->save();
        $message = $quest->is_active ? 'Quest berhasil diaktifkan kembali.' : 'Quest berhasil dijeda.';
        return redirect(route('quests.index') . '#createQuest')->with('success', $message);
    }

    private function calculateRewards($difficulty, $statType)
    {
        // Nilai default jika user tidak mengisi input angka
        $rewards = ['exp' => 0, 'gold' => 0, 'stat' => 0];
        switch ($difficulty) {
            case 'easy': $rewards = ['exp' => 10, 'gold' => 5, 'stat' => $statType ? 1 : 0]; break;
            case 'medium': $rewards = ['exp' => 100, 'gold' => 15, 'stat' => $statType ? 3 : 0]; break; // Medium = 100 EXP
            case 'hard': $rewards = ['exp' => 1000, 'gold' => 30, 'stat' => $statType ? 5 : 0]; break; // Hard = 1000 EXP
        }
        return $rewards;
    }
}