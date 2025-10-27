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
        // (DIUBAH) Mencari 'is_admin' = 0 (BUKAN 'role')
        // (DIUBAH) Mengurutkan berdasarkan 'experience_points' (BUKAN 'exp')
        $topUsers = User::where('is_admin', 0) 
                        ->orderBy('experience_points', 'desc')
                        ->orderBy('gold', 'desc')
                        ->take(20) 
                        ->get();

        // 2. Ambil data pengguna yang sedang login
        $currentUser = Auth::user();
        $currentUserRank = null;

        // 3. Temukan peringkat pengguna (jika dia BUKAN admin)
        // (DIUBAH) Mengecek 'is_admin' == 0
        if ($currentUser->is_admin == 0) {
            
            // (DIUBAH) Filter 'is_admin' = 0 dan urutkan berdasarkan 'experience_points'
            $rankedUserIds = User::where('is_admin', 0) 
                                 ->orderBy('experience_points', 'desc')
                                 ->orderBy('gold', 'desc')
                                 ->pluck('id')
                                 ->flip(); 

            if (isset($rankedUserIds[$currentUser->id])) {
                 $currentUserRank = $rankedUserIds[$currentUser->id] + 1;
            }
        }

        // 4. Kirim data ke view
        return view('leaderboard.index', [
            'topUsers' => $topUsers,
            'currentUser' => $currentUser,
            'currentUserRank' => $currentUserRank 
        ]);
    }
}