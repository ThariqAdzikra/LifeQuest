<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Support\Facades\Log;

class AchievementService
{
    /**
     * Periksa dan berikan achievement baru untuk user.
     *
     * @param User $user User yang baru saja menyelesaikan aksi (misal: quest).
     */
    public function checkAndGrantAchievements(User $user)
    {
        // 1. Ambil ID achievement yang sudah dimiliki user
        $unlockedAchievementIds = $user->achievements()->pluck('achievements.id')->toArray();

        // 2. Ambil semua achievement yang BELUM dimiliki user
        $lockedAchievements = Achievement::whereNotIn('id', $unlockedAchievementIds)->get();

        if ($lockedAchievements->isEmpty()) {
            return; // Tidak ada lagi achievement untuk diperiksa
        }

        // 3. Muat relasi questLogs dan refresh data user (untuk stats terbaru)
        $user->refresh();
        $user->load('questLogs'); // Memuat relasi questLogs

        foreach ($lockedAchievements as $achievement) {
            
            // Cek apakah kondisi JSON terpenuhi
            if ($this->checkCondition($user, $achievement->condition)) {
                
                // 4. KONDISI TERPENUHI: Berikan achievement
                // Kita hanya perlu attach. Database akan mengisi 'unlocked_at'
                // secara otomatis berdasarkan skema Anda
                $user->achievements()->attach($achievement->id);

                // 5. Berikan reward (jika ada) dari achievement
                $rewardApplied = false;
                if ($achievement->exp_reward > 0) {
                    $user->exp += $achievement->exp_reward;
                    $rewardApplied = true;
                }
                if ($achievement->gold_reward > 0) {
                    $user->gold += $achievement->gold_reward;
                    $rewardApplied = true;
                }
                
                // Simpan user HANYA jika ada reward baru
                if ($rewardApplied) {
                    $user->save();
                }
                
                // Opsional: Catat di log
                Log::info("User {$user->id} unlocked achievement: {$achievement->title}");
            }
        }
    }

    /**
     * Helper untuk mengecek kondisi JSON dari achievement.
     *
     * @param User $user
     * @param array|null $condition Kondisi dari kolom 'condition'
     * @return bool
     */
    private function checkCondition(User $user, $condition)
    {
        if (empty($condition) || !is_array($condition)) {
            return false; // Tidak ada kondisi, tidak bisa unlock
        }

        // --- Ini adalah RULE ENGINE sederhana ---
        // Anda bisa kembangkan ini sesuai kebutuhan

        // Contoh 1: Cek berdasarkan jumlah quest selesai
        if (isset($condition['quests_completed'])) {
            $completedCount = $user->questLogs->where('status', 'completed')->count();
            if ($completedCount >= $condition['quests_completed']) {
                return true;
            }
        }

        // Contoh 2: Cek berdasarkan stat (misal: 'strength' >= 50)
        if (isset($condition['stat']) && isset($condition['value'])) {
            $statName = $condition['stat']; // misal: 'strength'
            $requiredValue = $condition['value']; // misal: 50
            
            // Cek apakah user punya stat itu dan nilainya mencukupi
            if (isset($user->{$statName}) && $user->{$statName} >= $requiredValue) {
                return true;
            }
        }

        // Contoh 3: Cek berdasarkan total gold (gold saat ini)
        if (isset($condition['gold_earned'])) {
            if ($user->gold >= $condition['gold_earned']) {
                return true;
            }
        }
        
        // ... tambahkan rule lain di sini (misal: level, dll)

        return false; // Default, kondisi tidak terpenuhi
    }
}