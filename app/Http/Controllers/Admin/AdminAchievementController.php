<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 

class AdminAchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::latest()->paginate(10);
        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:achievements,title',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024', 
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('achievements/icons', 'public');
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            'key_name' => Str::slug($validated['title'], '_'), 
            'condition' => [], 
        ];

        Achievement::create($data); 

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement berhasil dibuat.');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
         $validated = $request->validate([
            'title' => 'required|string|max:255|unique:achievements,title,' . $achievement->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024', 
        ]);

        $iconPath = $achievement->icon_path; 

        if ($request->hasFile('icon')) {
            if ($achievement->icon_path && Storage::disk('public')->exists($achievement->icon_path)) {
                Storage::disk('public')->delete($achievement->icon_path);
            }
            $iconPath = $request->file('icon')->store('achievements/icons', 'public');
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            'key_name' => Str::slug($validated['title'], '_'), 
        ];

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->icon_path && Storage::disk('public')->exists($achievement->icon_path)) {
            Storage::disk('public')->delete($achievement->icon_path);
        }

        $achievement->quests()->update(['achievement_id' => null]);
        $achievement->users()->detach();
        $achievement->delete();

        return back()->with('success', 'Achievement berhasil dihapus.');
    }
}