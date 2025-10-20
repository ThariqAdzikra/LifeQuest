<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; // <-- DITAMBAHKAN

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil data user yang sedang login
        $user = $request->user();

        // Isi model user dengan data yang sudah divalidasi (nama & email)
        $user->fill($request->validated());

        // Jika user mengganti email, reset status verifikasi emailnya
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // --- [LOGIKA BARU UNTUK UPLOAD FOTO PROFIL] ---
        // Cek apakah ada file 'avatar' yang di-upload dalam request
        if ($request->hasFile('avatar')) {
            // Validasi file yang di-upload
            $request->validateWithBag('updateProfileInformation', [
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            // Hapus avatar lama dari storage jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan file avatar yang baru di folder 'storage/app/public/avatars'
            // dan simpan path-nya ke variabel
            $path = $request->file('avatar')->store('avatars', 'public');

            // Simpan path file yang baru ke kolom 'avatar' di database
            $user->avatar = $path;
        }
        // --- [AKHIR DARI LOGIKA BARU] ---

        // Simpan semua perubahan pada data user
        $user->save();

        // Redirect kembali ke halaman edit profil dengan pesan sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
