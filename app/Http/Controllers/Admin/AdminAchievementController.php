<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Import Str untuk membuat slug

class AdminAchievementController extends Controller
{
    /** Display a listing of achievements. */
    public function index()
    {
        $achievements = Achievement::latest()->paginate(15);
        return view('admin.achievements.index', compact('achievements'));
    }

    /** Show the form for creating a new achievement. */
    public function create()
    {
        return view('admin.achievements.create');
    }

    /** Store a newly created achievement in storage. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:achievements,title',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024', // Maks 1MB
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            // Simpan icon ke folder 'achievements/icons' di public storage
            $iconPath = $request->file('icon')->store('achievements/icons', 'public');
        }

        // Buat array data untuk menyertakan key_name
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            'key_name' => Str::slug($validated['title'], '_'), // Membuat 'key_name'
            'condition' => [], // <-- [PERBAIKAN KEDUA] Tambahkan baris ini
        ];

        Achievement::create($data); // Simpan data

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement berhasil dibuat.');
    }

    /** Show the form for editing the specified achievement. */
    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    /** Update the specified achievement in storage. */
    public function update(Request $request, Achievement $achievement)
    {
         $validated = $request->validate([
            'title' => 'required|string|max:255|unique:achievements,title,' . $achievement->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024', // Maks 1MB
        ]);

        $iconPath = $achievement->icon_path; // Default ke path lama

        if ($request->hasFile('icon')) {
            // Hapus ikon lama jika ada
            if ($achievement->icon_path && Storage::disk('public')->exists($achievement->icon_path)) {
                Storage::disk('public')->delete($achievement->icon_path);
            }
            // Simpan ikon baru
            $iconPath = $request->file('icon')->store('achievements/icons', 'public');
        }

        // Buat array data untuk update
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            'key_name' => Str::slug($validated['title'], '_'), // Update key_name
            // 'condition' tidak perlu di-update jika form edit tidak mengubahnya
        ];

        $achievement->update($data); // Update data

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement berhasil diperbarui.');
    }

    /** Remove the specified achievement from storage. */
    public function destroy(Achievement $achievement)
    {
        // Hapus ikon jika ada
        if ($achievement->icon_path && Storage::disk('public')->exists($achievement->icon_path)) {
            Storage::disk('public')->delete($achievement->icon_path);
        }

        // Hapus relasi dengan quest (set achievement_id jadi null)
        $achievement->quests()->update(['achievement_id' => null]);
        
        // Hapus achievement dari pivot table user
        $achievement->users()->detach();

        // Hapus achievement
        $achievement->delete();

        return back()->with('success', 'Achievement berhasil dihapus.');
    }
}