<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan ini sudah benar dan sangat penting
        $this->call([
            UserSeeder::class,        // 1. Membuat user (termasuk 'Test User')
            AchievementSeeder::class, // 2. Membuat achievement
            QuestSeeder::class,       // 3. Membuat quest (Admin & Pribadi untuk Test User)
            QuestLogSeeder::class,    // 4. Membuat log (Pending, Active, Rejected, Completed)
        ]);
    }
}