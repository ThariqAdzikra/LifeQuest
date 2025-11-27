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
            // 1. Simpan file fisik (hasilnya string relatif: "achievements/icons/namafile.png")
            $path = $request->file('icon')->store('achievements/icons', 'public');

            // 2. Tambahkan prefix 'storage/' agar sama formatnya dengan data Seeder
            $iconPath = 'storage/' . $path;
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            // Generate slug otomatis untuk key_name jika belum ada logic lain
            'key_name' => Str::slug($validated['title'], '_'),
            'condition' => [], // Default array kosong
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
            // HAPUS GAMBAR LAMA
            if ($achievement->icon_path) {
                // Kita harus membuang awalan 'storage/' karena Storage facade membaca relative dari root disk 'public'
                $relativePath = str_replace('storage/', '', $achievement->icon_path);

                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }

            // SIMPAN GAMBAR BARU
            $path = $request->file('icon')->store('achievements/icons', 'public');

            // Tambahkan prefix 'storage/' lagi agar konsisten di database
            $iconPath = 'storage/' . $path;
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            // Update key_name jika judul berubah (opsional, hati-hati jika key_name dipakai di logic codingan lain)
            'key_name' => Str::slug($validated['title'], '_'),
        ];

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->icon_path) {
            // Bersihkan path untuk penghapusan fisik
            $relativePath = str_replace('storage/', '', $achievement->icon_path);

            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        // Set null pada quest yang menggunakan achievement ini (agar tidak error constraint)
        $achievement->quests()->update(['achievement_id' => null]);

        // Hapus relasi dengan user (pivot table)
        $achievement->users()->detach();

        // Hapus data utama
        $achievement->delete();

        return back()->with('success', 'Achievement berhasil dihapus.');
    }
}