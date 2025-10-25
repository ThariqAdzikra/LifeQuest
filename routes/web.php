<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// Landing Page Route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Dashboard Route (menggunakan controller)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // --- PERUBAHAN DISINI ---
    // Kita tambahkan 'destroy' ke 'only()'
    Route::resource('quests', QuestController::class)->only([
        'index',   // (GET /quests) untuk menampilkan halaman quest board
        'store',   // (POST /quests) untuk menyimpan quest baru
        'destroy'  // (DELETE /quests/{quest}) untuk menghapus quest
    ]);
    
    // Rute kustom untuk Aksi Quest (Tetap sama)
    Route::post('/quests/{quest}/take', [QuestController::class, 'take'])->name('quests.take');
    Route::patch('/quest-logs/{questLog}/complete', [QuestController::class, 'complete'])->name('quests.complete');
    Route::delete('/quest-logs/{questLog}/cancel', [QuestController::class, 'cancel'])->name('quests.cancel');
    
    // --- AKHIR PERUBAHAN ---

    // Achievement Routes
    Route::resource('achievements', AchievementController::class);

    // Leaderboard Route
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

require __DIR__ . '/auth.php';