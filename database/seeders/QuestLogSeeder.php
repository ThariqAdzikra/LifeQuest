<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quest;
use App\Models\QuestLog;
use Carbon\Carbon;

class QuestLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // === BAGIAN 1: 50 SUBMISSION PENDING (UNTUK ADMIN REVIEW) ===
        
        // Ambil 50 user acak (BUKAN Test User atau Admin)
        $randomUsers = User::where('is_admin', false)
                            ->where('email', '!=', 'test@example.com')
                            ->inRandomOrder()
                            ->take(50) // Ambil 50 user
                            ->get();
        
        // Ambil 50 quest admin acak (pastikan ambil quest 'once')
        $adminQuests = Quest::where('is_admin_quest', true)
                            ->where('frequency', 'once') // Paling logis untuk submission
                            ->inRandomOrder()
                            ->take(50) // Ambil 50 quest
                            ->get();

        if ($randomUsers->isEmpty() || $adminQuests->isEmpty()) {
            $this->command->info('Tidak ada user atau quest admin yang cukup untuk membuat 50 log pending.');
        } else {
            foreach ($randomUsers as $index => $user) {
                // Jaga-jaga jika quest < 50, gunakan modulo
                if($adminQuests->count() == 0) continue;
                
                $quest = $adminQuests->get($index % $adminQuests->count());
                
                QuestLog::create([
                    'user_id' => $user->id,
                    'quest_id' => $quest->id,
                    'status' => 'pending', // Untuk halaman Admin Review
                    'completed_at' => null,
                    'submission_notes' => 'Halo admin, ini bukti quest ' . $quest->title . ' dari user ' . $user->name,
                    'submission_file_path' => 'submissions/fake_proof_random.jpg',
                    'admin_notes' => null,
                    'created_at' => $now->subMinutes(rand(5, 300)),
                    'updated_at' => $now->subMinutes(rand(1, 4)),
                ]);
            }
        }

        // === BAGIAN 2: DATA LOG UNTUK 'TEST USER' (45+ LOG) ===
        
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            
            // --- 5 Log Spesial (untuk tes UI) ---
            $questJalan = Quest::where('title', 'Jalan Pagi 15 Menit')->first();
            $questBaca = Quest::where('title', 'Baca Buku 30 Menit')->first();
            $questLatihan = Quest::where('title', 'Latihan Kekuatan Mingguan')->first();
            $questLari = Quest::where('title', 'Selesaikan Lari 5K')->first();
            $questPribadi = Quest::where('title', 'Deep Work 4 Jam (Pribadi)')->first();

            // 1. Quest 'active' (Admin)
            if ($questJalan) {
                QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questJalan->id],
                    ['status' => 'active', 'created_at' => $now, 'updated_at' => $now]
                );
            }
            // 2. Quest 'active' (Pribadi)
            if ($questPribadi) {
                 QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questPribadi->id],
                    ['status' => 'active', 'created_at' => $now, 'updated_at' => $now]
                );
            }
            // 3. Quest 'rejected'
            if ($questLatihan) {
                QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questLatihan->id],
                    [
                        'status' => 'rejected',
                        'submission_notes' => 'Ini bukti saya, admin.',
                        'submission_file_path' => 'submissions/fake_rejected.jpg',
                        'admin_notes' => 'Bukti foto tidak jelas, harap upload ulang foto yang lebih terang.',
                        'created_at' => $now->subDay(), 'updated_at' => $now
                    ]
                );
            }
            // 4. Quest 'completed'
            if ($questLari) {
                QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questLari->id],
                    [
                        'status' => 'completed',
                        'completed_at' => $now->subDays(1),
                        'created_at' => $now->subDays(2), 'updated_at' => $now->subDays(1)
                    ]
                );
            }

            // --- 45 Log 'Completed' Acak (untuk paginasi Riwayat) ---
            $completedQuests = Quest::where('is_admin_quest', true)
                                      ->where('id', '!=', $questLari ? $questLari->id : 0) // Jangan ambil quest yg sudah dipakai
                                      ->inRandomOrder()
                                      ->take(45)
                                      ->get();
            
            foreach($completedQuests as $quest) {
                QuestLog::create([
                    'user_id' => $testUser->id,
                    'quest_id' => $quest->id,
                    'status' => 'completed',
                    'completed_at' => $now->subDays(rand(2, 30)), // Selesai dalam 30 hari terakhir
                    'created_at' => $now->subDays(rand(31, 60)), 
                    'updated_at' => $now->subDays(rand(2, 30))
                ]);
            }
        }
    }
}