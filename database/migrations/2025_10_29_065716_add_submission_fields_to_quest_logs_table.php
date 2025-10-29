<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            // Kolom untuk file yg diupload (mis: 'submissions/file.jpg')
            $table->string('submission_file_path')->nullable()->after('status');
            // Kolom untuk catatan dari user
            $table->text('submission_notes')->nullable()->after('submission_file_path');
            
            // Ubah 'status' agar bisa menerima 'pending_review'
            // (Hapus ENUM jika Anda pakai string biasa)
            $table->string('status')->change(); // Ubah ke string agar lebih fleksibel
        });
    }

    public function down(): void
    {
        Schema::table('quest_logs', function (Blueprint $table) {
            $table->dropColumn(['submission_file_path', 'submission_notes']);
            // Anda mungkin perlu logika 'change' kembali ke ENUM di sini jika Anda menggunakannya
        });
    }
};