<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

class AchievementController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil juga daftar achievement yang sudah di-unlock oleh user
        // Kita gunakan relasi 'achievements' yang tadi dibuat di Model User
        // Kita juga memuat relasi 'questLogs' untuk menghitung quest yg selesai
        $user->load('questLogs'); 
        $unlockedAchievements = $user->achievements()->get();

        // Kirim data user DAN unlockedAchievements ke view
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