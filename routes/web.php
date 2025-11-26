<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// --- CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuestController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\AdminAchievementController;

// ========================================
// 1. PUBLIC ROUTE
// ========================================
Route::get('/', [LandingController::class, 'index'])->name('landing');


// ========================================
// 2. ROUTE KHUSUS USER BIASA (PLAYER)
// Syarat: Login ('auth') DAN Bukan Admin ('user')
// ========================================
Route::middleware(['auth', 'verified', 'user'])->group(function () {

    // Dashboard User (Player)
    // Jika Admin mencoba akses ini, akan ditendang ke Admin Dashboard oleh UserMiddleware
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GRUP ROUTE QUEST (PLAYER) ---
    Route::resource('quests', QuestController::class)->only(['index', 'store', 'destroy']);
    Route::post('/quests/{quest}/take', [QuestController::class, 'take'])->name('quests.take');
    Route::patch('/quest-logs/{questLog}/complete', [QuestController::class, 'complete'])->name('quests.complete');
    Route::post('/quest-logs/{questLog}/submit', [QuestController::class, 'submit'])->name('quests.submit');
    Route::delete('/quest-logs/{questLog}/cancel', [QuestController::class, 'cancel'])->name('quests.cancel');
    Route::patch('/quests/{quest}/toggle', [QuestController::class, 'toggleStatus'])->name('quests.toggleStatus');

    // Achievement (Player View)
    Route::resource('achievements', AchievementController::class)->only(['index']);

    // Route Leaderboard dipindahkan dari sini ke Section 3
});


// ========================================
// 3. ROUTE UMUM (BISA ADMIN & USER)
// ========================================
Route::middleware('auth')->group(function () {
    // Profile biasanya boleh diakses siapa saja yang login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leaderboard
    // Dipindahkan ke sini agar bisa diakses Admin DAN Player
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});


// ========================================
// 4. ROUTE KHUSUS ADMIN
// Syarat: Login ('auth') DAN Admin ('admin')
// ========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // /admin/dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // /admin/quests (CRUD Quest)
    Route::get('/quests', [AdminQuestController::class, 'index'])->name('quests.index');
    Route::get('/quests/create', [AdminQuestController::class, 'create'])->name('quests.create');
    Route::post('/quests', [AdminQuestController::class, 'store'])->name('quests.store');
    Route::get('/quests/{quest}/edit', [AdminQuestController::class, 'edit'])->name('quests.edit');
    Route::put('/quests/{quest}', [AdminQuestController::class, 'update'])->name('quests.update');
    Route::delete('/quests/{quest}', [AdminQuestController::class, 'destroy'])->name('quests.destroy');

    // /admin/submissions (Review)
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{questLog}/approve', [SubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{questLog}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');

    // /admin/achievements (CRUD Achievement)
    Route::resource('achievements', AdminAchievementController::class);
});

require __DIR__ . '/auth.php';