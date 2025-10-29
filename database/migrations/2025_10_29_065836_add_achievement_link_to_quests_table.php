<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            // Kolom untuk menautkan ke achievement
            $table->foreignId('achievement_id')
                  ->nullable()
                  ->after('stat_reward_value')
                  ->constrained('achievements') // Asumsi tabel Anda 'achievements'
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            $table->dropForeign(['achievement_id']);
            $table->dropColumn('achievement_id');
        });
    }
};