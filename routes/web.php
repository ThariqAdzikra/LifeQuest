<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\AchievementController; // Player Achievement Controller
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// --- TAMBAHAN CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuestController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\AdminAchievementController; // <-- [TAMBAHAN BARU] Import Admin Achievement Controller
// --- AKHIR TAMBAHAN ---


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

    
    // --- GRUP ROUTE QUEST ---
    Route::resource('quests', QuestController::class)->only([
        'index', 'store', 'destroy'
    ]);
    Route::post('/quests/{quest}/take', [QuestController::class, 'take'])->name('quests.take');
    Route::patch('/quest-logs/{questLog}/complete', [QuestController::class, 'complete'])->name('quests.complete');
    Route::post('/quest-logs/{questLog}/submit', [QuestController::class, 'submit'])->name('quests.submit');
    Route::delete('/quest-logs/{questLog}/cancel', [QuestController::class, 'cancel'])->name('quests.cancel');
    Route::patch('/quests/{quest}/toggle', [QuestController::class, 'toggleStatus'])->name('quests.toggleStatus');
    // --- AKHIR GRUP ROUTE QUEST ---


    // Achievement Routes (Player View)
    Route::resource('achievements', AchievementController::class)->only(['index']); // Player hanya bisa lihat index

    // Leaderboard Route
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});


// ========================================
// --- GRUP RUTE ADMIN (TAMBAHAN BARU) ---
// ========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // /admin/dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // /admin/quests (CRUD untuk Quest Admin)
    Route::resource('quests', AdminQuestController::class)->except(['show']);
    
    // /admin/submissions (Review Quest Submissions)
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{questLog}/approve', [SubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{questLog}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');

    // [TAMBAHAN BARU] /admin/achievements (CRUD untuk Achievements)
    Route::resource('achievements', AdminAchievementController::class); 

});
// --- AKHIR GRUP RUTE ADMIN ---


require __DIR__ . '/auth.php';