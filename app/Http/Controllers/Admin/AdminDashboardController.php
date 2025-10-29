<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // 1. Ambil jumlah user (yang bukan admin)
        $userCount = User::where('is_admin', false)->count();

        // 2. Ambil leaderboard (user non-admin)
        $leaderboard = User::where('is_admin', false)
                            ->orderBy('exp', 'desc')
                            ->take(10)
                            ->get();

        return view('admin.dashboard', compact('userCount', 'leaderboard'));
    }
}