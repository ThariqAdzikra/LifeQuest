@extends('layouts.app')

@section('title', 'Verify Email - HabitQuest')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    {{-- Tambahkan kelas 'auth-card--wide' di sini --}}
    <div class="auth-card auth-card--wide"> 
        <h2 class="auth-title">VERIFIKASI EMAIL ANDA</h2>

        <div class="info-text">
            Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="success-message">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif

        <div class="actions-container">
            <form method="POST" action="{{ route('verification.send') }}" style="flex-grow: 1;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection