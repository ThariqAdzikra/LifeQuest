<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quest; 
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::where('is_admin', false)->count();

        $leaderboard = User::where('is_admin', false)
                            ->orderBy('exp', 'desc')
                            ->take(10)
                            ->get();

        $adminQuestCount = Quest::whereNull('user_id')->count();

        return view('admin.dashboard', compact('userCount', 'leaderboard', 'adminQuestCount'));
    }
}