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
        Schema::table('quest_logs', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            $table->timestamp('completed_at')
                  ->nullable() // PENTING: Harus bisa null
                  ->after('status'); // Posisikan setelah 'status'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            // TAMBAHKAN INI UNTUK ROLLBACK
            $table->dropColumn('completed_at');
        });
    }
};