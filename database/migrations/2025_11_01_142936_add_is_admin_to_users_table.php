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
        Schema::table('users', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            $table->boolean('is_admin')
                  ->default(false) // PENTING: User baru otomatis bukan admin
                  ->after('password'); // Posisikan setelah 'password'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TAMBAHKAN INI UNTUK ROLLBACK
            $table->dropColumn('is_admin');
        });
    }
};