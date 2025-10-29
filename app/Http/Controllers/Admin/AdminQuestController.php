<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quest;
use App\Models\Achievement; // <-- [TAMBAHAN] Import Achievement
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQuestController extends Controller
{
    /**
     * FUNGSI LAMA: Menampilkan daftar semua quest admin.
     */
    public function index()
    {
        // Ambil semua quest yang is_admin_quest = true, urutkan dari terbaru
        $adminQuests = Quest::where('is_admin_quest', true)
                            ->latest()
                            ->paginate(10); // Kita pakai 10 per halaman

        // Kirim data ke view admin.quests.index
        return view('admin.quests.index', compact('adminQuests'));
    }

    /**
     * Menampilkan form untuk membuat quest admin baru.
     */
    public function create()
    {
        // [PERUBAHAN] Ambil semua achievement untuk dropdown
        $achievements = Achievement::orderBy('title')->get();
        
        // Kirim data achievements ke view
        return view('admin.quests.create', compact('achievements'));
    }

    /**
     * Menyimpan quest admin baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'frequency' => 'required|in:once,daily,weekly',
            'exp_reward' => 'required|integer|min:0',
            'gold_reward' => 'required|integer|min:0',
            'stat_reward_type' => 'nullable|in:intelligence,strength,stamina,agility,',
            'stat_reward_value' => 'nullable|integer|min:0|required_with:stat_reward_type',
            'achievement_id' => 'nullable|exists:achievements,id', // <-- [TAMBAHAN] Validasi
        ]);

        Quest::create([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'frequency' => $request->frequency,
            'exp_reward' => $request->exp_reward,
            'gold_reward' => $request->gold_reward,
            'stat_reward_type' => $request->stat_reward_type,
            'stat_reward_value' => $request->stat_reward_type ? $request->stat_reward_value : 0,
            'achievement_id' => $request->achievement_id, // <-- [TAMBAHAN] Simpan ID
            
            'is_admin_quest' => true,
            'creator_id' => null,
            'is_active' => true,
        ]);

        // Ganti redirect ke halaman daftar quest admin, bukan dashboard
        return redirect()->route('admin.quests.index')->with('success', 'Quest admin berhasil dibuat!');
    }
    
    /**
     * FUNGSI LAMA: Menghapus quest admin.
     */
    public function destroy(Quest $quest)
    {
        // Pastikan ini adalah quest admin
        if (!$quest->is_admin_quest) {
            return back()->with('error', 'Ini bukan quest admin dan tidak bisa dihapus.');
        }

        // Hapus semua log terkait quest ini agar tidak error
        $quest->logs()->delete();
        
        // Hapus quest
        $quest->delete();

        return back()->with('success', 'Quest admin berhasil dihapus.');
    }
}