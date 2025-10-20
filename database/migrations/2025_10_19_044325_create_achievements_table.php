<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('key_name', 50)->unique();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->json('condition');
            $table->integer('exp_reward')->default(0);
            $table->integer('gold_reward')->default(0);
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])->default('common');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
