<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// --- TAMBAHAN CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuestController;
use App\Http\Controllers\Admin\SubmissionController; // <-- [TAMBAHAN BARU] Import Submission Controller
// --- AKHIR TAMBAHAN ---


// Landing Page Route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Dashboard Route (menggunakan controller)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    // [PERBAIKAN] Mengganti Route. (titik) menjadi Route:: (titik dua)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // --- GRUP ROUTE QUEST ---
    
    // Route resource untuk index, store, dan destroy
    Route::resource('quests', QuestController::class)->only([
        'index',   // (GET /quests) untuk menampilkan halaman quest board
        'store',   // (POST /quests) untuk menyimpan quest baru
        'destroy'  // (DELETE /quests/{quest}) untuk menghapus quest
    ]);
    
    // Rute kustom untuk Aksi Quest
    Route::post('/quests/{quest}/take', [QuestController::class, 'take'])->name('quests.take');
    
    // [MODIFIKASI] 'complete' HANYA untuk quest non-admin (instan)
    Route::patch('/quest-logs/{questLog}/complete', [QuestController::class, 'complete'])->name('quests.complete');
    
    // [TAMBAHAN BARU] 'submit' untuk quest admin (upload file)
    Route::post('/quest-logs/{questLog}/submit', [QuestController::class, 'submit'])->name('quests.submit');
    
    Route::delete('/quest-logs/{questLog}/cancel', [QuestController::class, 'cancel'])->name('quests.cancel');
    
    // Route untuk Toggle (Jeda / Aktifkan) Quest Pribadi
    Route::patch('/quests/{quest}/toggle', [QuestController::class, 'toggleStatus'])->name('quests.toggleStatus');
    
    // --- AKHIR GRUP ROUTE QUEST ---


    // Achievement Routes
    Route::resource('achievements', AchievementController::class);

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
    
    // [TAMBAHAN BARU] Route untuk Review Submission
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{questLog}/approve', [SubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{questLog}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');
});
// --- AKHIR GRUP RUTE ADMIN ---


require __DIR__ . '/auth.php';