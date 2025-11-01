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
            $table->text('admin_notes')
                  ->nullable() // PENTING: Harus bisa null
                  ->after('submission_file_path'); // Posisi yang baik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            // TAMBAHKAN INI UNTUK ROLLBACK
            $table->dropColumn('admin_notes');
        });
    }
};