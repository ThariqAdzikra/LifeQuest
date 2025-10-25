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
            // Ubah kolom 'status' menjadi string (varchar) 
            // dengan panjang 20 (atau 255 jika Anda biarkan kosong).
            // .change() berarti kita mengubah kolom yang sudah ada.
            $table->string('status', 20)->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            // Ini adalah kebalikan dari perintah di atas
            // (Sesuaikan '5' jika panjang awal Anda berbeda)
            $table->string('status', 5)->change(); 
        });
    }
};