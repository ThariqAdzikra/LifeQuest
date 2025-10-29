<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class LeaderboardController extends Controller
{
    /**
     * Menampilkan halaman leaderboard.
     */
    public function index()
    {
        // 1. Ambil 20 pengguna teratas (NON-ADMIN)
        // [PERBAIKAN] Diurutkan berdasarkan jumlah quest admin yang selesai
        $topUsers = User::where('is_admin', 0) 
                        // [PERBAIKAN] Menggunakan nama relasi 'questLogs' dari User.php
                        ->withCount(['questLogs' => function ($query) {
                            $query->where('status', 'completed') // 1. Statusnya 'completed'
                                  // 2. Dan quest-nya adalah quest admin
                                  ->whereHas('quest', function ($q) {
                                      $q->where('is_admin_quest', true);
                                  });
                        }])
                        // [PERBAIKAN] Nama count default berubah menjadi 'quest_logs_count'
                        ->orderBy('quest_logs_count', 'desc')
                        // Jika ada yang sama, urutkan berdasarkan nama
                        ->orderBy('name', 'asc') 
                        ->take(20) 
                        ->get();

        // 2. Ambil data pengguna yang sedang login
        $currentUser = Auth::user();
        $currentUserRank = null;
        $currentUserQuestCount = 0; // Data baru untuk ditampilkan di kartu

        // 3. Temukan peringkat pengguna (jika dia BUKAN admin)
        if ($currentUser->is_admin == 0) {
            
            // A. Dapatkan jumlah quest admin yang diselesaikan user saat ini
            // [PERBAIKAN] Menggunakan nama relasi 'questLogs'
            $currentUserQuestCount = $currentUser->questLogs()
                ->where('status', 'completed')
                ->whereHas('quest', function ($q) {
                    $q->where('is_admin_quest', true);
                })->count();

            // B. Hitung berapa banyak user (non-admin) yang memiliki quest count > dari user saat ini
            $higherRankedUsers = User::where('is_admin', 0)
                // [PERBAIKAN] Menggunakan nama relasi 'questLogs'
                ->withCount(['questLogs' => function ($query) {
                    $query->where('status', 'completed')
                          ->whereHas('quest', function ($q) {
                              $q->where('is_admin_quest', true);
                          });
                }])
                // [PERBAIKAN UTAMA] Ganti 'where' menjadi 'having' untuk filter alias
                ->having('quest_logs_count', '>', $currentUserQuestCount)
                ->count();
            
            // C. Peringkatnya adalah (jumlah user di atasnya) + 1
            $currentUserRank = $higherRankedUsers + 1;
        }

        // 4. Kirim data ke view (termasuk data count baru)
        return view('leaderboard.index', [
            'topUsers' => $topUsers,
            'currentUser' => $currentUser,
            'currentUserRank' => $currentUserRank,
            'currentUserQuestCount' => $currentUserQuestCount // <-- Kirim data baru
        ]);
    }
}