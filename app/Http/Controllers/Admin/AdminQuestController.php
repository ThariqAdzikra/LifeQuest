<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quest;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQuestController extends Controller
{
    /**
     * Menampilkan daftar semua quest admin.
     */
    public function index()
    {
        // Ambil semua quest yang is_admin_quest = true, urutkan dari terbaru
        $adminQuests = Quest::where('is_admin_quest', true)
                            ->latest()
                            ->paginate(10); 

        // Kirim data ke view admin.quests.index
        return view('admin.quests.index', compact('adminQuests'));
    }

    /**
     * Menampilkan form untuk membuat quest admin baru.
     */
    public function create()
    {
        $achievements = Achievement::orderBy('title')->get();
        return view('admin.quests.create', compact('achievements'));
    }

    /**
     * Mengambil data quest untuk edit (AJAX)
     * [FIXED]
     */
    public function edit(Quest $quest)
    {
        if (!$quest->is_admin_quest) {
            return response()->json(['error' => 'Ini bukan quest admin'], 403);
        }

        // [FIX 1] Ambil SEMUA achievements untuk mengisi dropdown di modal
        $allAchievements = Achievement::orderBy('title', 'asc')->get(['id', 'title']);

        // [FIX 2] Kembalikan JSON dalam format yang diharapkan oleh quest.js
        return response()->json([
            'quest' => $quest, // Data quest yang spesifik
            'achievements' => $allAchievements // Daftar semua achievements
        ]);
    }

    /**
     * Update quest admin
     */
    public function update(Request $request, Quest $quest)
    {
        if (!$quest->is_admin_quest) {
            return back()->with('error', 'Ini bukan quest admin dan tidak bisa diubah.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'frequency' => 'required|in:once,daily,weekly',
            'exp_reward' => 'required|integer|min:0',
            'gold_reward' => 'required|integer|min:0',
            'stat_reward_type' => 'nullable|in:intelligence,strength,stamina,agility,',
            'stat_reward_value' => 'nullable|integer|min:0|required_with:stat_reward_type',
            'achievement_id' => 'nullable|exists:achievements,id',
        ]);

        $quest->update([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'frequency' => $request->frequency,
            'exp_reward' => $request->exp_reward,
            'gold_reward' => $request->gold_reward,
            'stat_reward_type' => $request->stat_reward_type,
            'stat_reward_value' => $request->stat_reward_type ? $request->stat_reward_value : 0,
            'achievement_id' => $request->achievement_id,
        ]);

        return redirect()->route('admin.quests.index')->with('success', 'Quest admin berhasil diperbarui!');
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
            'achievement_id' => 'nullable|exists:achievements,id',
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
            'achievement_id' => $request->achievement_id,
            
            'is_admin_quest' => true,
            'user_id' => null, 
            'is_active' => true,
        ]);

        return redirect()->route('admin.quests.index')->with('success', 'Quest admin berhasil dibuat!');
    }
    
    /**
     * Menghapus quest admin.
     */
    public function destroy(Quest $quest)
    {
        if (!$quest->is_admin_quest) {
            return back()->with('error', 'Ini bukan quest admin dan tidak bisa dihapus.');
        }

        $quest->logs()->delete();
        $quest->delete();

        return back()->with('success', 'Quest admin berhasil dihapus.');
    }
}