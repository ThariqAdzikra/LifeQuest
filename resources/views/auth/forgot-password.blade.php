@extends('layouts.app')

@section('title', 'Forgot Password - HabitQuest')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">LUPA PASSWORD</h2>

        <div class="info-text">
            Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </div>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="warrior@habitquest.com">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Kirim Tautan Reset
            </button>
        </form>
    </div>
</div>
@endsection