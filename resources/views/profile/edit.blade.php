@extends('layouts.app')

@section('title', 'Pengaturan Profil - LifeQuest')

@push('styles')
{{-- Referensi CSS Cropper.js dengan Fallback --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.css';" />

{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/profile/style.css') }}">
@endpush

@section('content')
    <div class="profile-container">
        
        <h1 class="profile-title">Pengaturan Profil</h1>

        {{-- Bagian Update Informasi Profil --}}
        <div class="profile-card">
            <header>
                <h2>Informasi Profil</h2>
                <p>Perbarui informasi profil dan alamat email akun Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-form">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label>Foto Profil</label>
                    <div class="avatar-upload-section">
                        <div class="avatar-preview-container">
                            @if (Auth::user()->avatar)
                                <img id="avatar-preview" src="{{ asset('storage/'. Auth::user()->avatar) }}" alt="Avatar Preview" class="avatar-preview-img">
                            @else
                                <div id="avatar-preview-placeholder" class="avatar-preview-placeholder">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="avatar-upload-actions">
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('avatar-input').click()">
                                Ganti Foto
                            </button>
                            <input id="avatar-input" 
                                   type="file" 
                                   name="avatar" 
                                   class="hidden-file-input" 
                                   accept="image/png, image/jpeg, image/jpg, image/gif">
                            <p class="avatar-upload-info">PNG, JPG, atau GIF (Maks. 2MB).<br>Akan dipotong menjadi 1:1.</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="input-field" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">
                                Email Anda belum terverifikasi.
                                <a href="{{ route('verification.send') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #00d4ff; text-decoration: underline;">
                                    Klik di sini untuk mengirim ulang email verifikasi.
                                </a>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1 text-sm" style="color: #39e988;">
                                    Tautan verifikasi baru telah dikirim ke alamat email Anda.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm" style="color: #39e988;">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>
        
        {{-- Bagian Update Password --}}
        <div class="profile-card">
            <header>
                <h2>Perbarui Password</h2>
                <p>Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" class="input-field" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input id="password" name="password" type="password" class="input-field" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="input-field" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm" style="color: #39e988;">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- ================================================= --}}
        {{-- [PERBAIKAN] Bagian Hapus Akun (Hanya untuk User Biasa) --}}
        {{-- ================================================= --}}
        @if (!Auth::user()->isAdmin())
            <div class="profile-card" x-data="{ confirmingUserDeletion: false }">
                <header>
                    <h2>Hapus Akun</h2>
                    <p>Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
                </header>
                
                <button type="button" class="btn btn-danger" @click="confirmingUserDeletion = true">
                    Hapus Akun Saya
                </button>

                <div x-show="confirmingUserDeletion" 
                     class="modal-overlay" 
                     style="display: none;">
                    <div class="modal-content" @click.away="confirmingUserDeletion = false">
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')

                            <h3 class="modal-title">Apakah Anda yakin?</h3>
                            <p class="modal-description">
                                Setelah akun Anda dihapus, semua data akan hilang permanen. 
                                Harap masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                            </p>

                            <div class="form-group">
                                <label for="password_delete">Password</label>
                                <input id="password_delete" name="password" type="password" class="input-field" placeholder="Password" required>
                                {{-- [MODIFIKASI] Gunakan error bag 'userDeletion' --}}
                                @error('password', 'userDeletion')
                                    <p class="text-sm" style="color: #ff4d4d;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" @click="confirmingUserDeletion = false">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    Hapus Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        {{-- --- AKHIR PERBAIKAN --- --}}

        {{-- Modal untuk Cropping Gambar --}}
        <div id="crop-modal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <h3 class="modal-title">Sesuaikan Foto Profil</h3>
                <p class="modal-description">
                    • Klik dan geser gambar untuk memposisikan<br>
                    • Gunakan scroll mouse/trackpad untuk zoom in/out<br>
                    • Geser sudut/sisi kotak biru untuk resize area crop
                </p>
                
                <div class="cropper-wrapper">
                    <img id="crop-image" src="" alt="Crop Image">
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="cancelCrop()">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="applyCrop()">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Referensi JS Cropper.js dengan Fallback --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="loadCropperFallback()"></script>

{{-- Memanggil file JS kustom --}}
<script src="{{ asset('js/profile/main.js') }}"></script>
@endpush