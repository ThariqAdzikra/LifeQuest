@extends('layouts.app')

@section('title', 'Register - HabitQuest')

@push('styles')
    {{-- Link ke CSS autentikasi kustom --}}
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    {{-- Font Awesome CDN untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">DAFTAR WARRIOR BARU</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Warrior</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="warrior@habitquest.com">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                {{-- BARU: Wrapper untuk ikon mata --}}
                <div class="input-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                    <i class="fas fa-eye toggle-password" data-target="password"></i>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                {{-- BARU: Wrapper untuk ikon mata --}}
                <div class="input-wrapper">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda">
                    <i class="fas fa-eye toggle-password" data-target="password_confirmation"></i>
                </div>
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">
                Mulai Petualangan
            </button>

            <div class="divider">
                <span>atau</span>
            </div>

            <div class="form-footer">
                <span style="color: #b0b0c0; font-size: 0.9rem;">Sudah punya akun?</span>
                <a href="{{ route('login') }}" style="margin-left: 0.5rem;">
                    Login Sekarang
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- BARU: JavaScript untuk toggle password --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleIcons = document.querySelectorAll('.toggle-password');

        toggleIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                // Dapatkan target input berdasarkan atribut data-target
                const targetInputId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetInputId);

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endpush