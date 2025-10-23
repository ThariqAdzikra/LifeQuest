@extends('layouts.app')

@section('title', 'Login - HabitQuest')

@push('styles')
    {{-- Link ke CSS autentikasi kustom --}}
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">🎮 LOGIN WARRIOR</h2>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="warrior@habitquest.com">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="checkbox-group">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label for="remember_me" class="ml-2 text-sm checkbox-label">{{ __('Ingat saya') }}</label>
            </div>

            <button type="submit" class="btn btn-primary">
                Masuk ke Quest
            </button>

            @if (Route::has('password.request'))
                <div class="form-footer">
                    <a href="{{ route('password.request') }}">
                        Lupa password Anda?
                    </a>
                </div>
            @endif

            <div class="divider">
                <span>atau</span>
            </div>

            <div class="form-footer">
                <span style="color: #b0b0c0; font-size: 0.9rem;">Belum punya akun?</span>
                <a href="{{ route('register') }}" style="margin-left: 0.5rem;">
                    Daftar Sekarang
                </a>
            </div>
        </form>
    </div>
</div>
@endsection