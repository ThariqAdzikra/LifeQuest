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
            $table->foreignId('user_id')
                  ->nullable() // 'nullable' agar quest admin bisa NULL
                  ->after('id') // Posisikan di dekat 'id' (opsional)
                  ->constrained('users') // Tautkan ke tabel 'users'
                  ->onDelete('cascade'); // Jika user dihapus, quest pribadinya ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            // TAMBAHKAN INI UNTUK ROLLBACK
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};