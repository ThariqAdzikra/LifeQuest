@extends('layouts.app')

@section('title', 'Dashboard - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

{{-- Memanggil file CSS kustom dari folder public --}}
<link rel="stylesheet" href="{{ asset('css/dashboard/style.css') }}">
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="container-xl px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card">
                    <div class="welcome-content">
                        <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}!</h1>
                        <p class="welcome-subtitle">Siap menaklukkan quest hari ini? Mari tingkatkan produktivitasmu!</p>
                        <div class="welcome-quote" id="motivational-quote">
                            <i class="bi bi-quote me-2"></i>
                            "Kesuksesan adalah hasil dari persiapan kecil yang dilakukan berulang kali."
                        </div>
                    </div>
                    {{-- --- PERUBAHAN DI SINI: Hapus d-none d-md-block --- --}}
                    <img src="{{ asset('images/char.png') }}" alt="Character" class="character-image">
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-list-task"></i>
                    </div>
                    {{-- Data dari DashboardController --}}
                    <div class="stat-value">{{ $totalQuests ?? 0 }}</div>
                    <div class="stat-label">Total Quest</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    {{-- Data dari DashboardController --}}
                    <div class="stat-value">{{ $completedQuests ?? 0 }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    {{-- Data dari DashboardController (hardcode 0) --}}
                    <div class="stat-value">{{ $achievements ?? 0 }}</div>
                    <div class="stat-label">Achievements</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    {{-- Data dari DashboardController --}}
                    <div class="stat-value">{{ $totalXP ?? 0 }}</div>
                    <div class="stat-label">Total XP</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            
            <div class="col-lg-8">
                <div class="widget-card p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="widget-header">
                                <i class="bi bi-clock"></i>
                                <h3 class="widget-title">Waktu Real-time</h3>
                            </div>
                            <div class="clock-display" id="clock">--:--:--</div>
                            <div class="date-display" id="date">Loading...</div>
                        </div>

                        <div class="col-md-6">
                            <div class="widget-header">
                                <i class="bi bi-cloud-sun"></i>
                                <h3 class="widget-title">Cuaca Hari Ini</h3>
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

            <div class="col-lg-4">
                <div class="widget-card">
                    <div class="widget-header">
                        <i class="bi bi-fire"></i>
                        <h3 class="widget-title">Streak Harian</h3>
                    </div>
                    <div class="streak-display">
                        <div class="streak-icon">🔥</div>
                        <div class="streak-info">
                            {{-- Data dari DashboardController (hardcode 0) --}}
                            <div class="streak-value">{{ $currentStreak ?? 0 }}</div>
                            <div class="streak-label">hari berturut-turut</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="glass-card p-4">
                    <div class="section-header">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h2 class="section-title">Progress Anda</h2>
                    </div>

                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="bi bi-calendar-check"></i>
                                Quest Harian
                            </span>
                            {{-- Data dari DashboardController --}}
                            <span class="progress-percentage">{{ $totalQuests > 0 ? round(($completedQuests / $totalQuests) * 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            {{-- Data dari DashboardController --}}
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalQuests > 0 ? ($completedQuests / $totalQuests) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="bi bi-lightning-charge"></i>
                                Target XP Mingguan (500 XP)
                            </span>
                            {{-- --- PERUBAHAN DI SINI: Gunakan $weeklyXP --- --}}
                            <span class="progress-percentage">{{ $weeklyXP > 0 ? min(round(($weeklyXP / 500) * 100), 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            {{-- --- PERUBAHAN DI SINI: Gunakan $weeklyXP --- --}}
                            <div class="progress-bar" role="progressbar" style="width: {{ $weeklyXP > 0 ? min(($weeklyXP / 500) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="bi bi-award"></i>
                                Pencapaian Terbuka (0/10)
                            </span>
                            {{-- Data dari DashboardController --}}
                            <span class="progress-percentage">{{ $achievements > 0 ? min(round(($achievements / 10) * 100), 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            {{-- Data dari DashboardController --}}
                            <div class="progress-bar" role="progressbar" style="width: {{ $achievements > 0 ? min(($achievements / 10) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <div class="section-header">
                        <i class="bi bi-clock-history"></i>
                        <h2 class="section-title">Aktivitas Terbaru</h2>
                    </div>
                    
                    <div class="activity-list-wrapper">
                        {{-- --- PERUBAHAN DI SINI: Integrasi data $recentActivities --- --}}
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            {{-- $activity adalah model QuestLog --}}
                            @foreach($recentActivities as $activity)
                            <div class="activity-card">
                                <div class="activity-icon-wrapper">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="activity-content">
                                    {{-- Ambil 'title' dari relasi 'quest' --}}
                                    <div class="activity-title">{{ $activity->quest->title }}</div>
                                    <div class="activity-time">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{-- Gunakan 'updated_at' (waktu complete) & format --}}
                                        {{ $activity->updated_at->format('d F Y, H:i') }}
                                    </div>
                                </div>
                                {{-- Ambil 'exp_reward' dari relasi 'quest' --}}
                                <div class="activity-xp">+{{ $activity->quest->exp_reward }} XP</div>
                            </div>
                            @endforeach
                        @else
                            {{-- Tampilan jika tidak ada aktivitas --}}
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="empty-state-title">Belum Ada Aktivitas</div>
                                <div class="empty-state-text">Mulai quest pertamamu sekarang!</div>
                            </div>
                        @endif
                        {{-- --- AKHIR PERUBAHAN --- --}}
                    </div> 
                </div>
            </div>
        </div>

        </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Memanggil file JS kustom dari folder public --}}
<script src="{{ asset('js/dashboard/main.js') }}"></script>
@endpush