<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quest; 
use App\Models\Achievement;
use App\Models\User;

class QuestSeeder extends Seeder
{
    public function run(): void
    {
        
        $firstStepAchievement = Achievement::where('key_name', 'first_quest_completed')->first();

        Quest::updateOrCreate(
            ['title' => 'Mulai Perjalananmu'],
            [
                'description' => 'Selesaikan aktivitas positif pertamamu dan log di LifeQuest.',
                'difficulty' => 'easy', 'frequency' => 'once', 'exp_reward' => 50, 'gold_reward' => 20,
                'stat_reward_type' => null, 'stat_reward_value' => 0,
                'achievement_id' => $firstStepAchievement ? $firstStepAchievement->id : null,
                'is_admin_quest' => true,
                'is_active' => true,
            ]
        );

        $stats = ['intelligence', 'strength', 'stamina', 'agility', null];
        $difficulties = ['easy', 'medium', 'hard'];
        $frequencies = ['once', 'daily', 'weekly'];

        for ($i = 1; $i <= 49; $i++) {
            $freq = $frequencies[array_rand($frequencies)];
            $stat = $stats[array_rand($stats)];
            
            Quest::create([
                'title' => 'Tugas Admin Acak #' . $i . ' (' . ucfirst($freq) . ')',
                'description' => 'Deskripsi untuk tugas admin acak nomor ' . $i . '.',
                'difficulty' => $difficulties[array_rand($difficulties)],
                'frequency' => $freq,
                'exp_reward' => rand(50, 300),
                'gold_reward' => rand(20, 100),
                'stat_reward_type' => $stat,
                'stat_reward_value' => $stat ? rand(1, 5) : 0,
                'achievement_id' => null,
                'is_admin_quest' => true,
                'is_active' => true,
            ]);
        }
        
        $testUser = User::where('email', 'test@example.com')->first();

        if ($testUser) {
            Quest::updateOrCreate(
                ['title' => 'Review Target Harian (Pribadi)', 'user_id' => $testUser->id],
                [
                    'description' => 'Cek kembali target harian dan pastikan semua tercapai.',
                    'difficulty' => 'easy', 'frequency' => 'daily', 'exp_reward' => 10, 'gold_reward' => 5,
                    'stat_reward_type' => 'intelligence', 'stat_reward_value' => 1,
                    'is_admin_quest' => false, 'is_active' => true,
                ]
            );
            Quest::updateOrCreate(
                ['title' => 'Deep Work 4 Jam (Pribadi)', 'user_id' => $testUser->id],
                [
                    'description' => 'Lakukan 4 jam kerja fokus tanpa distraksi.',
                    'difficulty' => 'hard', 'frequency' => 'weekly', 'exp_reward' => 200, 'gold_reward' => 100,
                    'stat_reward_type' => 'intelligence', 'stat_reward_value' => 5,
                    'is_admin_quest' => false, 'is_active' => true,
                ]
            );
            Quest::updateOrCreate(
                ['title' => 'Meditasi Pagi (Pribadi)', 'user_id' => $testUser->id],
                [
                    'description' => 'Meditasi 10 menit setiap pagi.',
                    'difficulty' => 'easy', 'frequency' => 'daily', 'exp_reward' => 15, 'gold_reward' => 5,
                    'stat_reward_type' => null, 'stat_reward_value' => 0,
                    'is_admin_quest' => false, 'is_active' => false, 
                ]
            );
             for ($i = 1; $i <= 47; $i++) {
                $freq = $frequencies[array_rand($frequencies)];
                $stat = $stats[array_rand($stats)];

                Quest::create([
                    'title' => 'Tugas Pribadi #' . $i . ' (' . ucfirst($freq) . ')',
                    'description' => 'Deskripsi untuk tugas pribadi acak nomor ' . $i . '.',
                    'difficulty' => $difficulties[array_rand($difficulties)],
                    'frequency' => $freq,
                    'exp_reward' => rand(10, 50), 
                    'gold_reward' => rand(5, 25),
                    'stat_reward_type' => $stat,
                    'stat_reward_value' => $stat ? rand(1, 3) : 0,
                    'user_id' => $testUser->id,
                    'is_admin_quest' => false,
                    'is_active' => true,
                ]);
            }
        }
    }
}