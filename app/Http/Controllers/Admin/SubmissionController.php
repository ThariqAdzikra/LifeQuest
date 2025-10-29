<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Menampilkan semua submission yang 'pending_review'.
     */
    public function index()
    {
        $submissions = QuestLog::where('status', 'pending_review')
                                ->with(['user', 'quest']) // Load relasi user & quest
                                ->latest()
                                ->paginate(20);
                                
        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * Menyetujui submission.
     */
    public function approve(QuestLog $questLog)
    {
        // Pastikan statusnya benar
        if ($questLog->status !== 'pending_review') {
            return back()->with('error', 'Submission ini sudah diproses.');
        }

        $user = $questLog->user;
        $quest = $questLog->quest;

        // 1. Berikan Reward ke User
        $user->exp += $quest->exp_reward;
        $user->gold += $quest->gold_reward;
        if ($quest->stat_reward_type) {
            $stat = $quest->stat_reward_type;
            $user->{$stat} += $quest->stat_reward_value;
        }
        
        // 2. Berikan Achievement (Title)
        if ($quest->achievement_id) {
            // 'syncWithoutDetaching' agar tidak duplikat
            $user->achievements()->syncWithoutDetaching($quest->achievement_id, [
                'unlocked_at' => now() // Pastikan pivot table diisi
            ]);
        }
        
        $user->save();

        // 3. Ubah status log
        $questLog->status = 'completed';
        $questLog->save();

        return back()->with('success', 'Submission disetujui. Reward & Title telah diberikan.');
    }

    /**
     * Menolak submission.
     */
    public function reject(QuestLog $questLog)
    {
        // Pastikan statusnya benar
        if ($questLog->status !== 'pending_review') {
            return back()->with('error', 'Submission ini sudah diproses.');
        }

        // Hapus file submission yang lama
        if ($questLog->submission_file_path) {
            Storage::disk('public')->delete($questLog->submission_file_path);
        }

        // Kembalikan status ke 'active' agar user bisa submit ulang
        $questLog->status = 'active';
        $questLog->submission_file_path = null;
        $questLog->submission_notes = null;
        $questLog->save();

        // (Opsional: kirim notifikasi ke user kenapa ditolak)

        return back()->with('success', 'Submission ditolak dan dikembalikan ke user.');
    }
}