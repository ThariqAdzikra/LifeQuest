@extends('layouts.app')

@section('title', 'Admin Dashboard - LifeQuest')

@push('styles')
{{-- Import Google Fonts sesuai Style Guide --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS kustom untuk dashboard --}}
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<div class="quest-board-container">

    {{-- [PERUBAHAN] Judul H1 dan Subjudul P utama telah dihapus --}}

    {{-- [PERUBAHAN] Welcome Card (Isi disesuaikan) --}}
    <div class="glass-card welcome-card mb-4">
        <div class="welcome-content">
            {{-- [PERUBAHAN] "Selamat Datang" dipindahkan ke sini --}}
            <h2 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}!</h2> 
            
            {{-- [PERUBAHAN] Judul "Overview" dan Subjudul "Pantau" dihapus --}}
            
            {{-- ID ini penting untuk main.js --}}
            <div class="welcome-quote" id="motivational-quote">
                <i class="bi bi-quote me-2"></i> Loading quote...
            </div>
        </div>
        <img src="{{ asset('images/char.png') }}" alt="Character" class="character-image">
    </div>


    {{-- [PERUBAHAN URUTAN] Widget Jam & Cuaca dipindahkan ke ATAS --}}
    <div class="section-header">
        <i class="bi bi-gear-wide-connected"></i>
        <h2 class="section-title">
            Widget
        </h2>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="row g-4">
                    
                    {{-- Kolom Jam --}}
                    <div class="col-md-6">
                        <div class="widget-header">
                            <i class="bi bi-clock"></i>
                            <h3 class="widget-title">Waktu Real-time</h3>
                        </div>
                        <div class="clock-display" id="clock">--:--:--</div>
                        <div class="date-display" id="date">Loading...</div>
                    </div>

                    {{-- Kolom Cuaca --}}
                    <div class="col-md-6">
                        <div class="widget-header">
                            <i class="bi bi-cloud-sun"></i>
                            <h3 class="widget-title">Cuaca (Pekanbaru)</h3>
                        </div>
                        <div id="weather-content">
                            <div class="loading-spinner">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Memuat data cuaca...</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- [PERUBAHAN URUTAN] Kartu Statistik sekarang di BAWAH widget --}}
    <div class="section-header">
        <i class="bi bi-bar-chart-line-fill"></i>
        <h2 class="section-title">
            Ringkasan Sistem
        </h2>
    </div>
    
    <div class="admin-stats-grid">
        {{-- Kartu 1: Total Pengguna Aktif --}}
        <div class="glass-card admin-stat-card">
            <h3>Total Pengguna Aktif</h3>
            <p class="stat-value">
                {{ $userCount }}
            </p>
            <small class="stat-label">(Tidak termasuk admin)</small>
        </div>

        {{-- Kartu 2: Total Quest Admin --}}
        <div class="glass-card admin-stat-card">
            <h3>Total Quest Admin</h3>
            <p class="stat-value">
                {{ $adminQuestCount ?? 0 }}
            </p>
            <small class="stat-label">(Jumlah quest resmi yang dibuat)</small>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Memanggil file main.js agar widget berfungsi --}}
<script src="{{ asset('js/dashboard/main.js') }}"></script>
@endpush