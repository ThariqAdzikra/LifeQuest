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
            // Cek dulu untuk keamanan
            if (!Schema::hasColumn('quests', 'stat_reward_type')) {
                $table->string('stat_reward_type')->nullable()->after('gold_reward');
            }
            if (!Schema::hasColumn('quests', 'stat_reward_value')) {
                $table->integer('stat_reward_value')->default(0)->after('stat_reward_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            // Cek dulu sebelum drop
            if (Schema::hasColumn('quests', 'stat_reward_type')) {
                $table->dropColumn('stat_reward_type');
            }
            if (Schema::hasColumn('quests', 'stat_reward_value')) {
                $table->dropColumn('stat_reward_value');
            }
        });
    }
};