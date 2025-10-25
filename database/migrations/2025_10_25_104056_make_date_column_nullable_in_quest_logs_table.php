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
            // Mengubah kolom 'date' agar Boleh Kosong (nullable)
            // Sesuaikan tipe datanya jika bukan ->date(). 
            // Mungkin ->timestamp('date') atau ->datetime('date')?
            // Saya asumsikan tipenya 'date'.
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            // Kembalikan ke kondisi semula (tidak boleh kosong)
            $table->date('date')->nullable(false)->change();
        });
    }
};