@extends('layouts.app')

@section('title', 'HabitQuest - Jejak Kebiasaan Positif Anda')

@push('styles')
    {{-- Tambahkan CDN untuk Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    {{-- Link ke CSS landing page kustom --}}
    <link rel="stylesheet" href="{{ asset('css/landing/landing.css') }}">
@endpush

@section('content')
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>MULAI PETUALANGAN ANDA</h1>
            <p>Raih kebiasaan positif dan naik level dalam kehidupan. Setiap hari adalah misi baru untuk menjadi versi terbaik dari diri Anda!</p>
            <div class="cta-buttons">
                {{-- Emoji 🎮 diganti dengan ikon Bootstrap --}}
                <a href="{{ route('register') }}" class="btn btn-primary"><i class="bi bi-joystick"></i> Mulai Sekarang</a>
                {{-- Emoji 📖 diganti dengan ikon Bootstrap --}}
                <a href="#features" class="btn btn-secondary"><i class="bi bi-book"></i> Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">FITUR UTAMA QUEST</h2>
            <div class="features-grid">
                
                {{-- Emoji 📊 diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                    <h3>Tracking Progres</h3>
                    <p>Pantau perkembangan kebiasaan positif Anda dengan dashboard yang intuitif dan real-time analytics.</p>
                </div>

                {{-- Emoji 🏆 diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-trophy-fill"></i></div>
                    <h3>Sistem Reward</h3>
                    <p>Dapatkan poin, badge, dan achievement untuk setiap kebiasaan yang Anda selesaikan setiap hari.</p>
                </div>

                {{-- Emoji 🎯 diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-bullseye"></i></div>
                    <h3>Target Harian</h3>
                    <p>Tetapkan target kebiasaan harian Anda dan lihat berapa persen progres Anda dalam mencapainya.</p>
                </div>

                {{-- Emoji ⚡ diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h3>Streak Tracker</h3>
                    <p>Jangan putus streak Anda! Lihat berapa hari berturut-turut Anda telah menjalankan kebiasaan positif.</p>
                </div>

                {{-- Emoji 👥 diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-people-fill"></i></div>
                    <h3>Komunitas</h3>
                    <p>Bergabunglah dengan komunitas warrior lainnya dan saling mendukung dalam perjalanan transformasi diri.</p>
                </div>

                {{-- Emoji 📈 diganti dengan ikon Bootstrap --}}
                <div class="feature-card fade-in">
                    <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Analitik Mendalam</h3>
                    <p>Dapatkan insight lengkap tentang pola kebiasaan Anda dengan grafik dan statistik yang komprehensif.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Link ke JS landing page kustom --}}
    <script src="{{ asset('js/landing/landing.js') }}"></script>
@endpush