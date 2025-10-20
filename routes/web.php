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

    // Quest Routes
    Route::resource('quests', QuestController::class);

    // Achievement Routes
    Route::resource('achievements', AchievementController::class);

    // Leaderboard Route
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

require __DIR__ . '/auth.php';