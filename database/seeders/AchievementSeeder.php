<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'key_name' => 'first_quest_completed',
                'title' => 'Pejuang Hari Pertama',
                'description' => 'Selesaikan aktivitas (quest) pertamamu.',
                'icon_path' => 'storage/achievements/icons/first-step.png',
                'exp_reward' => 50,
                'gold_reward' => 20,
                'rarity' => 'common',
                'condition' => json_encode(['type' => 'quest_completed', 'count' => 1]),
            ],
            [
                'key_name' => 'daily_10_streak',
                'title' => 'Rajin Harian',
                'description' => 'Selesaikan 10 quest harian secara berturut-turut.',
                'icon_path' => 'storage/achievements/icons/daily-streak.png',
                'exp_reward' => 200,
                'gold_reward' => 100,
                'rarity' => 'rare',
                'condition' => json_encode(['type' => 'quest_streak', 'frequency' => 'daily', 'count' => 10]),
            ],
            [
                'key_name' => 'quest_master_25',
                'title' => 'Petualang Sejati',
                'description' => 'Selesaikan 25 quest unik (sekali jalan).',
                'icon_path' => 'storage/achievements/icons/quest-master.png',
                'exp_reward' => 500,
                'gold_reward' => 250,
                'rarity' => 'epic',
                'condition' => json_encode(['type' => 'quest_completed', 'frequency' => 'once', 'count' => 25]),
            ],
            [
                'key_name' => 'intellect_1',
                'title' => 'Sang Intelektual (T1)',
                'description' => 'Capai total 50 poin Intelligence.',
                'icon_path' => 'storage/achievements/icons/intellect-1.png',
                'exp_reward' => 200,
                'gold_reward' => 0,
                'rarity' => 'rare',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'intelligence', 'value' => 50]),
            ],
            [
                'key_name' => 'intellect_2',
                'title' => 'Sang Intelektual (T2)',
                'description' => 'Capai total 250 poin Intelligence.',
                'icon_path' => 'storage/achievements/icons/intellect-2.png',
                'exp_reward' => 1000,
                'gold_reward' => 0,
                'rarity' => 'epic',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'intelligence', 'value' => 250]),
            ],
            [
                'key_name' => 'intellect_3',
                'title' => 'Pustakawan Agung (T3)',
                'description' => 'Capai total 1000 poin Intelligence.',
                'icon_path' => 'storage/achievements/icons/intellect-3.png',
                'exp_reward' => 5000,
                'gold_reward' => 1000,
                'rarity' => 'legendary',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'intelligence', 'value' => 1000]),
            ],
            [
                'key_name' => 'strength_1',
                'title' => 'Si Kuat (T1)',
                'description' => 'Capai total 50 poin Strength.',
                'icon_path' => 'storage/achievements/icons/strength-1.png',
                'exp_reward' => 200,
                'gold_reward' => 0,
                'rarity' => 'rare',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'strength', 'value' => 50]),
            ],
            [
                'key_name' => 'strength_2',
                'title' => 'Si Kuat (T2)',
                'description' => 'Capai total 250 poin Strength.',
                'icon_path' => 'storage/achievements/icons/strength-2.png',
                'exp_reward' => 1000,
                'gold_reward' => 0,
                'rarity' => 'epic',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'strength', 'value' => 250]),
            ],
            [
                'key_name' => 'strength_3',
                'title' => 'Hercules (T3)',
                'description' => 'Capai total 1000 poin Strength.',
                'icon_path' => 'storage/achievements/icons/strength-3.png',
                'exp_reward' => 5000,
                'gold_reward' => 1000,
                'rarity' => 'legendary',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'strength', 'value' => 1000]),
            ],
            [
                'key_name' => 'stamina_1',
                'title' => 'Atlet Tangguh (T1)',
                'description' => 'Capai total 50 poin Stamina.',
                'icon_path' => 'storage/achievements/icons/stamina-1.png',
                'exp_reward' => 200,
                'gold_reward' => 0,
                'rarity' => 'rare',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'stamina', 'value' => 50]),
            ],
            [
                'key_name' => 'stamina_2',
                'title' => 'Atlet Tangguh (T2)',
                'description' => 'Capai total 250 poin Stamina.',
                'icon_path' => 'storage/achievements/icons/stamina-2.png',
                'exp_reward' => 1000,
                'gold_reward' => 0,
                'rarity' => 'epic',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'stamina', 'value' => 250]),
            ],
            [
                'key_name' => 'stamina_3',
                'title' => 'Manusia Maraton (T3)',
                'description' => 'Capai total 1000 poin Stamina.',
                'icon_path' => 'storage/achievements/icons/stamina-3.png',
                'exp_reward' => 5000,
                'gold_reward' => 1000,
                'rarity' => 'legendary',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'stamina', 'value' => 1000]),
            ],
            [
                'key_name' => 'agility_1',
                'title' => 'Sang Gesit (T1)',
                'description' => 'Capai total 50 poin Agility.',
                'icon_path' => 'storage/achievements/icons/agility-1.png',
                'exp_reward' => 200,
                'gold_reward' => 0,
                'rarity' => 'rare',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'agility', 'value' => 50]),
            ],
            [
                'key_name' => 'agility_2',
                'title' => 'Sang Gesit (T2)',
                'description' => 'Capai total 250 poin Agility.',
                'icon_path' => 'storage/achievements/icons/agility-2.png',
                'exp_reward' => 1000,
                'gold_reward' => 0,
                'rarity' => 'epic',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'agility', 'value' => 250]),
            ],
            [
                'key_name' => 'agility_3',
                'title' => 'Bayangan (T3)',
                'description' => 'Capai total 1000 poin Agility.',
                'icon_path' => 'storage/achievements/icons/agility-3.png',
                'exp_reward' => 5000,
                'gold_reward' => 1000,
                'rarity' => 'legendary',
                'condition' => json_encode(['type' => 'stat_reached', 'stat' => 'agility', 'value' => 1000]),
            ],
        ];

        foreach ($achievements as $data) {
            Achievement::updateOrCreate(
                ['key_name' => $data['key_name']],
                $data
            );
        }
    }
}