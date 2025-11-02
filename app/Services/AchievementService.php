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
        $unlockedAchievementIds = $user->achievements()->pluck('achievements.id')->toArray();
        $lockedAchievements = Achievement::whereNotIn('id', $unlockedAchievementIds)->get();

        if ($lockedAchievements->isEmpty()) {
            return;
        }
        $user->refresh();
        $user->load('questLogs'); 

        foreach ($lockedAchievements as $achievement) {
            if ($this->checkCondition($user, $achievement->condition)) {
                $user->achievements()->attach($achievement->id);

                $rewardApplied = false;
                if ($achievement->exp_reward > 0) {
                    $user->exp += $achievement->exp_reward;
                    $rewardApplied = true;
                }
                if ($achievement->gold_reward > 0) {
                    $user->gold += $achievement->gold_reward;
                    $rewardApplied = true;
                }
                if ($rewardApplied) {
                    $user->save();
                }
                
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
            return false; 
        }

        if (isset($condition['quests_completed'])) {
            $completedCount = $user->questLogs->where('status', 'completed')->count();
            if ($completedCount >= $condition['quests_completed']) {
                return true;
            }
        }

        if (isset($condition['stat']) && isset($condition['value'])) {
            $statName = $condition['stat']; 
            $requiredValue = $condition['value']; 
            
            if (isset($user->{$statName}) && $user->{$statName} >= $requiredValue) {
                return true;
            }
        }

        if (isset($condition['gold_earned'])) {
            if ($user->gold >= $condition['gold_earned']) {
                return true;
            }
        }

        return false; 
    }
}