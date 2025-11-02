<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = QuestLog::where('status', 'pending_review')
                                ->with(['user', 'quest']) 
                                ->latest()
                                ->paginate(10); 
                                
        return view('admin.submissions.index', compact('submissions'));
    }

    public function approve(QuestLog $questLog)
    {
        if ($questLog->status !== 'pending_review') {
            return back()->with('error', 'Submission ini sudah diproses.');
        }

        $user = $questLog->user;
        $quest = $questLog->quest;

        $user->exp += $quest->exp_reward;
        $user->gold += $quest->gold_reward;
        if ($quest->stat_reward_type) {
            $stat = $quest->stat_reward_type;
            $user->{$stat} += $quest->stat_reward_value;
        }
        
        if ($quest->achievement_id) {
            $user->achievements()->syncWithoutDetaching($quest->achievement_id, [
                'unlocked_at' => now() 
            ]);
        }
        
        $user->save();

        $questLog->status = 'completed';
        $questLog->save();

        return back()->with('success', 'Submission disetujui. Reward & Title telah diberikan.');
    }

    public function reject(QuestLog $questLog)
    {
        if ($questLog->status !== 'pending_review') {
            return back()->with('error', 'Submission ini sudah diproses.');
        }

        if ($questLog->submission_file_path) {
            Storage::disk('public')->delete($questLog->submission_file_path);
        }

        $questLog->status = 'active';
        $questLog->submission_file_path = null;
        $questLog->submission_notes = null;
        $questLog->save();

        return back()->with('success', 'Submission ditolak dan dikembalikan ke user.');
    }
}