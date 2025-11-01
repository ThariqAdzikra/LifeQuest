<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            $table->boolean('is_active')
                  ->default(true) // Quest baru otomatis aktif
                  ->after('is_admin_quest'); // Posisikan setelah 'is_admin_quest'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            // TAMBAHKAN INI UNTUK ROLLBACK
            $table->dropColumn('is_active');
        });
    }
};