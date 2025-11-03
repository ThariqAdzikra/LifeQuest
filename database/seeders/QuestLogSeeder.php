<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quest;
use App\Models\QuestLog;
use Carbon\Carbon;

class QuestLogSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $randomUsers = User::where('is_admin', false)
                            ->where('email', '!=', 'test@example.com')
                            ->inRandomOrder()
                            ->take(50) 
                            ->get();
        
        $adminQuests = Quest::where('is_admin_quest', true)
                            ->where('frequency', 'once') 
                            ->inRandomOrder()
                            ->take(50) 
                            ->get();

        if ($randomUsers->isEmpty() || $adminQuests->isEmpty()) {
            $this->command->info('Tidak ada user atau quest admin yang cukup untuk membuat 50 log pending.');
        } else {
            foreach ($randomUsers as $index => $user) {
                if($adminQuests->count() == 0) continue;
                
                $quest = $adminQuests->get($index % $adminQuests->count());
                
                QuestLog::create([
                    'user_id' => $user->id,
                    'quest_id' => $quest->id,
                    'status' => 'pending', 
                    'completed_at' => null,
                    'submission_notes' => 'Halo admin, ini bukti quest ' . $quest->title . ' dari user ' . $user->name,
                    'submission_file_path' => 'submissions/fake_proof_random.jpg',
                    'admin_notes' => null,
                    'created_at' => $now->subMinutes(rand(5, 300)),
                    'updated_at' => $now->subMinutes(rand(1, 4)),
                ]);
            }
        }
        
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $questJalan = Quest::where('title', 'Jalan Pagi 15 Menit')->first();
            $questBaca = Quest::where('title', 'Baca Buku 30 Menit')->first();
            $questLatihan = Quest::where('title', 'Latihan Kekuatan Mingguan')->first();
            $questLari = Quest::where('title', 'Selesaikan Lari 5K')->first();
            $questPribadi = Quest::where('title', 'Deep Work 4 Jam (Pribadi)')->first();

            if ($questJalan) {
                QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questJalan->id],
                    ['status' => 'active', 'created_at' => $now, 'updated_at' => $now]
                );
            }
            if ($questPribadi) {
                 QuestLog::updateOrCreate(
                    ['user_id' => $testUser->id, 'quest_id' => $questPribadi->id],
                    ['status' => 'active', 'created_at' => $now, 'updated_at' => $now]
                );
            }
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
            $completedQuests = Quest::where('is_admin_quest', true)
                                      ->where('id', '!=', $questLari ? $questLari->id : 0) 
                                      ->inRandomOrder()
                                      ->take(45)
                                      ->get();
            
            foreach($completedQuests as $quest) {
                QuestLog::create([
                    'user_id' => $testUser->id,
                    'quest_id' => $quest->id,
                    'status' => 'completed',
                    'completed_at' => $now->subDays(rand(2, 30)),
                    'created_at' => $now->subDays(rand(31, 60)), 
                    'updated_at' => $now->subDays(rand(2, 30))
                ]);
            }
        }
    }
}