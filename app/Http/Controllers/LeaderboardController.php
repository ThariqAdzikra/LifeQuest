<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 20 pengguna teratas (NON-ADMIN) untuk tabel
        $topUsers = User::where('is_admin', 0)
            ->withCount([
                'questLogs' => function ($query) {
                    $query->where('status', 'completed')
                        ->whereHas('quest', function ($q) {
                            $q->where('is_admin_quest', true);
                        });
                }
            ])
            ->orderBy('quest_logs_count', 'desc') // Urutkan berdasarkan jumlah quest
            ->orderBy('name', 'asc') // Tie-breaker: urutkan nama A-Z
            ->take(20)
            ->get();

        // 2. Ambil data pengguna yang sedang login
        $currentUser = Auth::user();
        $currentUserRank = null;
        $currentUserQuestCount = 0;

        // 3. Hitung peringkat pengguna (Hanya jika dia BUKAN admin)
        // Admin tidak ikut dalam peringkat leaderboard
        if ($currentUser->is_admin == 0) {

            // A. Hitung Quest Admin milik user saat ini
            $currentUserQuestCount = $currentUser->questLogs()
                ->where('status', 'completed')
                ->whereHas('quest', function ($q) {
                    $q->where('is_admin_quest', true);
                })->count();

            // B. Hitung User yang rank-nya LEBIH TINGGI (Poin lebih banyak)
            // Menggunakan get() lalu count() untuk memastikan 'having' terbaca dengan benar
            $usersWithMorePoints = User::where('is_admin', 0)
                ->withCount([
                    'questLogs' => function ($query) {
                        $query->where('status', 'completed')
                            ->whereHas('quest', function ($q) {
                                $q->where('is_admin_quest', true);
                            });
                    }
                ])
                ->having('quest_logs_count', '>', $currentUserQuestCount)
                ->get();

            // C. Hitung User yang rank-nya LEBIH TINGGI karena Tie-Breaker (Poin SAMA tapi Nama lebih Awal)
            $usersWithSamePointsButHigherName = User::where('is_admin', 0)
                ->withCount([
                    'questLogs' => function ($query) {
                        $query->where('status', 'completed')
                            ->whereHas('quest', function ($q) {
                                $q->where('is_admin_quest', true);
                            });
                    }
                ])
                ->having('quest_logs_count', '=', $currentUserQuestCount)
                ->where('name', '<', $currentUser->name) // Nama 'A' ranknya lebih tinggi dari 'B'
                ->get();

            // Total user di atas kita + 1 = Peringkat Kita
            $currentUserRank = $usersWithMorePoints->count() + $usersWithSamePointsButHigherName->count() + 1;
        }

        // Kirim data ke view
        return view('leaderboard.index', [
            'topUsers' => $topUsers,
            'currentUser' => $currentUser,
            'currentUserRank' => $currentUserRank,
            'currentUserQuestCount' => $currentUserQuestCount
        ]);
    }
}