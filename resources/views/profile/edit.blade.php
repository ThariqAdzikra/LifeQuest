@extends('layouts.app')

@section('title', 'Pengaturan Profil - LifeQuest')

@push('styles')
{{-- [BARU] Tambahkan CSS untuk Cropper.js --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" xintegrity="sha512-hvNR0F/e2Jb1hbIDDdYRZYPCJH3hJo0rdnKbmLoAOKJW9a+J8cVKIYMAgF/DbjdQRJtbNanEMdonZTMGgPV5KA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    }
    .profile-card input[type="text"],
    .profile-card input[type="email"],
    .profile-card input[type="password"] {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: #e0e0e0;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }
    .profile-card input:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    }
    .btn-primary {
        padding: 0.8rem 1.8rem;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        color: #0a0e27;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
    }
    
    /* [BARU] Styling untuk Modal Cropper */
    .cropper-modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(10, 14, 39, 0.8);
        backdrop-filter: blur(5px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }
    .cropper-modal-content {
        background: #1a1a2e;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
    }
    .cropper-container {
        max-height: 400px;
        margin-bottom: 1.5rem;
    }
    /* Sembunyikan gambar asli yang akan digantikan cropper */
    #cropper-image {
        display: block;
        max-width: 100%;
    }
    .cropper-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    .btn-secondary {
        padding: 0.7rem 1.5rem;
        border-radius: 6px;
        border: 1px solid #a78bfa;
        color: #a78bfa;
        background: transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: rgba(167, 139, 250, 0.1);
    }
</style>
@endpush

@section('content')
    {{-- [BARU] Inisialisasi Alpine.js & Cropper.js --}}
    <div class="profile-container" x-data="imageCropper()">
        <h1 class="profile-title">Pengaturan Profil</h1>

        <div class="space-y-6">
            <div class="profile-card">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="profile-card">
                @include('profile.partials.update-password-form')
            </div>
            <div class="profile-card">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        {{-- [BARU] Modal untuk Cropping Gambar --}}
        <div x-show="showModal" @keydown.escape.window="showModal = false" class="cropper-modal-overlay" style="display: none;">
            <div class="cropper-modal-content" @click.away="showModal = false">
                <h3 class="font-bold text-xl mb-4 text-white">Sesuaikan Foto Profil</h3>
                <div class="cropper-container">
                    <img id="cropper-image" :src="imageUrl" alt="Image to crop">
                </div>
                <div class="cropper-modal-actions">
                    <button type="button" class="btn-secondary" @click="showModal = false">Batal</button>
                    <button type="button" class="btn-primary" @click="cropAndSave()">Potong & Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- [BARU] Tambahkan JS untuk Cropper.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" xintegrity="sha512-9KkIqdfN7ipEW6B6k+Aq20PVSAaPrufK4HiOMAIKdehbolJ3sGUVoXTGQGte/stZpLNeuAbaRe/xZ0HunsfTQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function imageCropper() {
        return {
            showModal: false,
            imageUrl: '',
            cropper: null,
            
            // Fungsi untuk menangani saat user memilih file
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                        this.showModal = true;
                        // Tunggu modal muncul, lalu inisialisasi cropper
                        this.$nextTick(() => {
                            if (this.cropper) this.cropper.destroy();
                            
                            const image = document.getElementById('cropper-image');
                            this.cropper = new Cropper(image, {
                                aspectRatio: 1, // Rasio 1:1 (persegi)
                                viewMode: 1,
                                background: false,
                                autoCropArea: 0.9,
                            });
                        });
                    };
                    reader.readAsDataURL(file);
                }
            },

            // Fungsi saat tombol "Potong & Simpan" di modal diklik
            cropAndSave() {
                this.cropper.getCroppedCanvas({
                    width: 512, // Ukuran hasil crop
                    height: 512,
                }).toBlob((blob) => {
                    const fileInput = document.getElementById('avatar');
                    const newFile = new File([blob], 'avatar.png', { type: 'image/png' });

                    // Ganti file di input asli dengan file yang sudah dicrop
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(newFile);
                    fileInput.files = dataTransfer.files;

                    // Update pratinjau di halaman profil secara langsung
                    const preview = document.getElementById('avatar-preview');
                    if (preview) {
                        preview.src = URL.createObjectURL(blob);
                    }

                    this.showModal = false; // Tutup modal
                }, 'image/png');
            }
        }
    }
</script>
@endpush

