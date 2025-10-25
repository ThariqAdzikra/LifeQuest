@extends('layouts.app')

@section('title', 'Pengaturan Profil - LifeQuest')

@push('styles')
{{-- Referensi CSS Cropper.js dengan Fallback --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.css';" />

<style>
    .profile-container {
        padding: 120px 2rem 4rem 2rem;
        max-width: 800px;
        margin: 0 auto;
    }
    .profile-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .profile-card {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 2.5rem;
        margin-bottom: 2rem;
    }
    .profile-card header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #fff;
    }
    .profile-card header p {
        color: #b0b0c0;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .profile-card label {
        display: block;
        margin-bottom: 0.5rem;
        color: #b0b0c0;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .profile-card .input-field {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: #e0e0e0;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }
    .profile-card .input-field:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    /* Styling untuk tombol-tombol RPG/Sci-fi */
    .btn {
        padding: 0.8rem 1.8rem;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    .btn-primary {
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        color: #0a0e27;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
    }
    .btn-secondary {
        padding: 0.7rem 1.5rem;
        border: 1px solid #a78bfa;
        color: #a78bfa;
        background: transparent;
    }
    .btn-secondary:hover {
        background: rgba(167, 139, 250, 0.1);
        transform: translateY(-2px);
    }
    .btn-danger {
        background: linear-gradient(135deg, #ff4d4d, #ff8c00);
        color: #fff;
    }
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 77, 77, 0.4);
    }
    
    /* Styling untuk upload avatar */
    .avatar-upload-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .avatar-preview-container {
        flex-shrink: 0;
    }
    .avatar-preview-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(0, 212, 255, 0.5);
        background-color: rgba(0, 0, 0, 0.3);
    }
    .avatar-preview-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        color: #0a0e27;
        border: 3px solid rgba(0, 212, 255, 0.5);
    }
    .avatar-upload-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .avatar-upload-actions .btn-secondary {
        width: max-content;
    }
    .avatar-upload-info {
        font-size: 0.8rem;
        color: #b0b0c0;
        margin-top: 0.25rem;
    }
    .hidden-file-input {
        display: none;
    }

    /* Styling untuk Modal (Cropper & Delete) */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(10, 14, 39, 0.95);
        backdrop-filter: blur(5px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .modal-content {
        background: #1a1a2e;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 2rem;
        max-width: 650px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 212, 255, 0.2);
    }
    .modal-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #fff;
    }
    .modal-description {
        color: #b0b0c0;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    /* Cropper Container Styling */
    .cropper-wrapper {
        width: 100%;
        height: 400px;
        margin-bottom: 1rem;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cropper-wrapper img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }

    /* Cropper.js Custom Styling */
    .cropper-container {
        direction: ltr;
        font-size: 0;
        line-height: 0;
        position: relative;
        touch-action: none;
        user-select: none;
    }

    .cropper-view-box {
        outline: 2px solid #00d4ff;
        outline-color: rgba(0, 212, 255, 0.75);
    }

    .cropper-point {
        background-color: #00d4ff;
        opacity: 1;
        width: 8px;
        height: 8px;
    }

    .cropper-line {
        background-color: #00d4ff;
        opacity: 0.5;
    }

    .cropper-point.point-se {
        background-color: #00d4ff;
        width: 12px;
        height: 12px;
    }

    .cropper-dashed {
        border: 0 dashed rgba(0, 212, 255, 0.5);
    }

    .cropper-dashed.dashed-h {
        border-top-width: 1px;
        border-bottom-width: 1px;
    }

    .cropper-dashed.dashed-v {
        border-left-width: 1px;
        border-right-width: 1px;
    }

    .text-sm { font-size: 0.875rem; }
    .text-gray-600 { color: #b0b0c0; }
    .mt-4 { margin-top: 1rem; }
    .mt-1 { margin-top: 0.25rem; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .gap-4 { gap: 1rem; }
</style>
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

        {{-- Bagian Hapus Akun --}}
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

<script>
// Fallback jika CDN utama gagal
function loadCropperFallback() {
    console.warn('Primary CDN failed, trying fallback...');
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.js';
    script.onload = function() {
        console.log('✅ Cropper loaded from fallback CDN');
    };
    script.onerror = function() {
        console.error('❌ All CDN sources failed');
    };
    document.head.appendChild(script);
}

// Tunggu sampai Cropper.js ter-load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Cek berkala apakah Cropper sudah tersedia
    let checkCount = 0;
    const checkInterval = setInterval(function() {
        checkCount++;
        
        if (typeof Cropper !== 'undefined') {
            console.log('✅ Cropper.js loaded successfully!');
            clearInterval(checkInterval);
        } else if (checkCount > 20) {
            console.error('❌ Cropper.js failed to load after 2 seconds');
            clearInterval(checkInterval);
        }
    }, 100);
});

let cropper = null;
let currentFile = null;

// Event listener untuk file input
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (!file) {
        return;
    }

    // Validasi tipe file
    if (!file.type.match('image.*')) {
        console.error('File bukan gambar');
        e.target.value = '';
        return;
    }

    // Validasi ukuran file (2MB)
    if (file.size > 2 * 1024 * 1024) {
        console.error('File terlalu besar (max 2MB)');
        e.target.value = '';
        return;
    }

    currentFile = file;
    
    // Baca file dan tampilkan di cropper
    const reader = new FileReader();
    reader.onload = function(event) {
        const imageUrl = event.target.result;
        showCropModal(imageUrl);
    };
    reader.readAsDataURL(file);
});

// Fungsi untuk menampilkan modal crop
function showCropModal(imageUrl) {
    // Cek apakah Cropper tersedia
    if (typeof Cropper === 'undefined') {
        console.error('Cropper is not defined');
        return;
    }

    const modal = document.getElementById('crop-modal');
    const image = document.getElementById('crop-image');
    
    // Set image source
    image.src = imageUrl;
    
    // Tampilkan modal
    modal.style.display = 'flex';
    
    // Destroy cropper lama jika ada
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    // Tunggu gambar dimuat, lalu inisialisasi cropper
    image.onload = function() {
        console.log('Image loaded, initializing cropper...');
        
        try {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                responsive: true,
                checkOrientation: true,
                modal: true,
                background: true,
                scalable: true,
                zoomable: true,
                zoomOnWheel: true,
                wheelZoomRatio: 0.1,
                minCropBoxWidth: 100,
                minCropBoxHeight: 100,
                ready: function() {
                    console.log('✅ Cropper initialized successfully!');
                }
            });
        } catch (error) {
            console.error('Error initializing cropper:', error);
        }
    };
    
    image.onerror = function() {
        console.error('Failed to load image');
        modal.style.display = 'none';
    };
}

// Fungsi untuk membatalkan crop
function cancelCrop() {
    const modal = document.getElementById('crop-modal');
    modal.style.display = 'none';
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    // Reset file input
    document.getElementById('avatar-input').value = '';
    currentFile = null;
    
    console.log('Crop cancelled');
}

// Fungsi untuk apply crop
function applyCrop() {
    if (!cropper) {
        console.error('Cropper is not initialized');
        return;
    }

    console.log('Applying crop...');

    try {
        // Dapatkan canvas hasil crop
        const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512,
            minWidth: 256,
            minHeight: 256,
            maxWidth: 4096,
            maxHeight: 4096,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            throw new Error('Failed to create canvas');
        }

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            if (!blob) {
                console.error('Failed to create blob');
                return;
            }

            console.log('Blob created successfully');

            // Buat file baru dari blob
            const fileName = 'avatar_' + Date.now() + '.png';
            const croppedFile = new File([blob], fileName, { type: 'image/png' });

            // Update input file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);
            document.getElementById('avatar-input').files = dataTransfer.files;

            // Update preview
            const blobUrl = URL.createObjectURL(blob);
            updatePreview(blobUrl);

            // Tutup modal
            document.getElementById('crop-modal').style.display = 'none';
            
            // Destroy cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            console.log('✅ Crop applied successfully!');
            
        }, 'image/png', 0.95);

    } catch (error) {
        console.error('Error during crop:', error);
    }
}

// Fungsi untuk update preview (HANYA di halaman profil, BUKAN navbar)
function updatePreview(blobUrl) {
    // Update HANYA preview di halaman profil
    const previewImg = document.getElementById('avatar-preview');
    const previewPlaceholder = document.getElementById('avatar-preview-placeholder');

    if (previewImg) {
        // Jika sudah ada img element, update src
        previewImg.src = blobUrl;
        // Pastikan img element visible
        previewImg.style.display = 'block';
    } else if (previewPlaceholder) {
        // Jika masih placeholder, replace dengan img
        const newImg = document.createElement('img');
        newImg.id = 'avatar-preview';
        newImg.src = blobUrl;
        newImg.alt = 'Avatar Preview';
        newImg.className = 'avatar-preview-img';
        previewPlaceholder.parentNode.replaceChild(newImg, previewPlaceholder);
    }
    
    console.log('✅ Preview updated in profile page only');
    console.log('Note: Navbar will update automatically after page reload from server');
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('crop-modal');
        if (modal.style.display === 'flex') {
            cancelCrop();
        }
    }
});

// Prevent default drag behavior
document.addEventListener('dragover', function(e) {
    e.preventDefault();
});

document.addEventListener('drop', function(e) {
    e.preventDefault();
});
</script>
@endpush