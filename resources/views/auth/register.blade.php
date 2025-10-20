@extends('layouts.app')

@section('title', 'Register - HabitQuest')

@push('styles')
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 120px 2rem 2rem 2rem;
        background: linear-gradient(rgba(10, 14, 39, 0.7), rgba(26, 26, 46, 0.8)), url('{{ asset("images/heroLanding.jpg") }}') center/cover;
        background-attachment: fixed;
    }

    .auth-card {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 12px;
        padding: 2.5rem;
        backdrop-filter: blur(10px);
        width: 100%;
        max-width: 450px;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #b0b0c0;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: #e0e0e0;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    }

    .btn {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        color: #0a0e27;
    }

     .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
    }

    .form-footer {
        text-align: center;
        margin-top: 1.5rem;
    }

    .form-footer a {
        color: #00d4ff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-footer a:hover {
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
    }

    .divider {
        text-align: center;
        margin: 1.5rem 0;
        color: #707080;
        display: flex;
        align-items: center;
    }

    .divider span { padding: 0 1rem; }

    .divider::before, .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .error-message {
        color: #ff4d4d;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>
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
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda">
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

