<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quest_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quest_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'completed', 'missed'])->default('pending');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quest_logs');
    }
};
