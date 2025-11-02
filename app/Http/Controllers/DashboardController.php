<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quest;
use App\Models\QuestLog;
use Carbon\Carbon;
use App\Models\Achievement;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data yang diagregasi.
     */
    public function index()
    {
        // ==========================================================
        // [PENTING] Redirect admin ke panel mereka SEBELUM
        // menghitung statistik pengguna.
        // ==========================================================
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        // ==========================================================
        // --- AKHIR PENTING ---
        // ==========================================================

        $userId = Auth::id();

        // --- 1. Data untuk Stat Cards (Kiri Atas) ---
        
        // Total quest yang pernah diambil (aktif + selesai)
        $totalQuests = QuestLog::where('user_id', $userId)->count();
        
        // Total quest yang sudah selesai
        $completedQuests = QuestLog::where('user_id', $userId)->where('status', 'completed')->count();
        
        // Menghitung Total XP dari semua quest yang telah selesai
        $totalXP = Quest::join('quest_logs', 'quests.id', '=', 'quest_logs.quest_id')
                        ->where('quest_logs.user_id', $userId)
                        ->where('quest_logs.status', 'completed')
                        ->sum('quests.exp_reward');
        
        // Hitung achievement user dari pivot table 'user_achievements'
        $achievements = DB::table('user_achievements')->where('user_id', $userId)->count();

        // Hitung total achievement yang dibuat admin
        $totalAchievements = Achievement::count(); 

        
        // --- 2. Data untuk Progress Bars ---
        
        // Menghitung quest yang diambil hari ini (semua tipe)
        $totalQuestsToday = QuestLog::where('user_id', $userId)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();
        
        // Menghitung quest yang diambil HARI INI & sudah SELESEI
        $completedQuestsToday = QuestLog::where('user_id', $userId)
                                        ->whereDate('created_at', Carbon::today())
                                        ->where('status', 'completed')
                                        ->count();

        // Menghitung XP yang didapat HARI INI
        $dailyXP = Quest::join('quest_logs', 'quests.id', '=', 'quest_logs.quest_id')
                         ->where('quest_logs.user_id', $userId)
                         ->where('quest_logs.status', 'completed')
                         ->whereDate('quest_logs.updated_at', Carbon::today()) // Cek yg selesai HARI INI
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
        
        // Perhatikan bahwa tidak ada lagi variabel notifikasi yang dikirim dari sini
        return view('dashboard', compact(
            'totalQuests',
            'completedQuests',
            'totalXP',
            'achievements',         
            'totalAchievements',    
            'dailyXP',              
            'totalQuestsToday',     
            'completedQuestsToday', 
            'currentStreak',
            'recentActivities'
        ));
    }
}