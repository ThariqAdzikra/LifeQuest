<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quest;     // Import model Quest
use App\Models\QuestLog;  // Import model QuestLog
use Carbon\Carbon;        // Import Carbon untuk mengambil data mingguan

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data yang diagregasi.
     */
    public function index()
    {
        // ==========================================================
        // [PENYESUAIAN] Redirect admin ke panel mereka SEBELUM
        // menghitung statistik pengguna.
        // ==========================================================
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        // ==========================================================
        // --- AKHIR PENYESUAIAN ---
        // ==========================================================

        $userId = Auth::id();

        // --- 1. Data untuk Stat Cards (Kiri Atas) ---
        
        // Total quest yang pernah diambil (aktif + selesai)
        $totalQuests = QuestLog::where('user_id', $userId)->count();
        
        // Total quest yang sudah selesai
        $completedQuests = QuestLog::where('user_id', $userId)->where('status', 'completed')->count();
        
        // Menghitung Total XP dari semua quest yang telah selesai
        // Kita join tabel quest_logs dengan tabel quests
        $totalXP = Quest::join('quest_logs', 'quests.id', '=', 'quest_logs.quest_id')
                        ->where('quest_logs.user_id', $userId)
                        ->where('quest_logs.status', 'completed')
                        ->sum('quests.exp_reward'); // Ambil jumlah 'exp_reward'

        // TODO: Ganti logika hardcode ini saat fitur achievement sudah ada
        $achievements = 0; 

        
        // --- 2. Data untuk Progress Bars ---
        
        // Menghitung XP yang didapat minggu ini (dimulai dari hari Senin)
        $weeklyXP = Quest::join('quest_logs', 'quests.id', '=', 'quest_logs.quest_id')
                         ->where('quest_logs.user_id', $userId)
                         ->where('quest_logs.status', 'completed')
                         ->where('quest_logs.updated_at', '>=', Carbon::now()->startOfWeek()) // Cek yg selesai minggu ini
                         ->sum('quests.exp_reward');

                         
        // --- 3. Data untuk Widget (Streak) ---
        
        // TODO: Ganti logika hardcode ini saat fitur streak harian sudah ada
        $currentStreak = 0; 

        
        // --- 4. Data untuk "Aktivitas Terbaru" ---
        
        $recentActivities = QuestLog::where('user_id', $userId)
                                    ->where('status', 'completed')
                                    ->with('quest') // Eager load relasi quest (untuk nama & XP)
                                    ->latest('updated_at') // Urutkan dari yg terbaru diselesaikan
                                    ->limit(5) // Ambil 5 terakhir
                                    ->get();

                                    
        // --- 5. Kirim semua data ke view ---
        
        // Ganti array lama Anda dengan yang ini
        return view('dashboard', compact(
            'totalQuests',
            'completedQuests',
            'totalXP',
            'achievements',
            'weeklyXP',        // Untuk progress bar mingguan
            'currentStreak',
            'recentActivities' // Untuk daftar aktivitas
        ));
    }
}