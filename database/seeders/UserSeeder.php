<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat User khusus untuk testing
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false, // Pastikan Test User bukan admin
        ]);

        // 2. Membuat 50 User player acak tambahan
        User::factory(50)->create([
             'is_admin' => false, // Pastikan semua player acak bukan admin
        ]);
        
        // 3. (Opsional) Buat 1 User Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true, // Tandai sebagai admin
        ]);
    }
}