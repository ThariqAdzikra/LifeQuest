@extends('layouts.app')

@section('title', 'Verify Email - HabitQuest')

@push('styles')
{{-- Paste the same <style> block from login.blade.php here --}}
<style>
    .auth-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 120px 2rem 2rem 2rem; background: linear-gradient(rgba(10, 14, 39, 0.7), rgba(26, 26, 46, 0.8)), url('{{ asset("images/heroLanding.jpg") }}') center/cover; background-attachment: fixed; }
    .auth-card { background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1)); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 12px; padding: 2.5rem; backdrop-filter: blur(10px); width: 100%; max-width: 500px; animation: fadeInUp 0.5s ease; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .auth-title { font-family: 'Orbitron', sans-serif; font-size: 1.8rem; text-align: center; margin-bottom: 1.5rem; background: linear-gradient(135deg, #00d4ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .info-text { color: #b0b0c0; text-align: center; margin-bottom: 2rem; line-height: 1.6; }
    .btn { width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: center; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #00d4ff, #a78bfa); color: #0a0e27; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4); }
    .success-message { background: rgba(0, 255, 150, 0.1); border: 1px solid rgba(0, 255, 150, 0.3); color: #00ff96; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; text-align: center; }
    .actions-container { margin-top: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .logout-btn { background: none; border: none; color: #a78bfa; cursor: pointer; text-decoration: underline; font-size: 0.9rem; padding: 0.5rem; transition: color 0.3s ease; }
    .logout-btn:hover { color: #00d4ff; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
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