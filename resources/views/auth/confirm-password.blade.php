@extends('layouts.app')

@section('title', 'Confirm Password - HabitQuest')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">KONFIRMASI PASSWORD</h2>
        
        <div class="info-text">
            Ini adalah area aman dari aplikasi. Harap konfirmasi kata sandi Anda sebelum melanjutkan.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Konfirmasi
            </button>
        </form>
    </div>
</div>
@endsection