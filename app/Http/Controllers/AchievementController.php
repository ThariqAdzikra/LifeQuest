<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('questLogs'); 
        $unlockedAchievements = $user->achievements()->get();

        return view('achievements.index', compact('user', 'unlockedAchievements'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}