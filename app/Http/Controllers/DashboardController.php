<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard', [
            'totalQuests' => 0,
            'completedQuests' => 0,
            'achievements' => 0,
            'totalXP' => 0,
            'currentStreak' => 0
        ]);
    }
}