@extends('layouts.app')

@section('title', 'Dashboard - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    body {
        background-color: #0a0e27;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
        background-image: url('/images/heroLanding.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        filter: blur(10px);
        -webkit-filter: blur(10px);
        opacity: 0.3;
    }

    .dashboard-wrapper {
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Glass Card Effect */
    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: all 0.3s ease;
        height: 100%; /* Menyamakan tinggi card */
    }

    .glass-card:hover {
        border-color: rgba(0, 212, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 212, 255, 0.1);
    }

    /* Welcome Section */
    .welcome-card {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1) 0%, rgba(15, 23, 42, 0.7) 100%);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0, 212, 255, 0.2);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    /* === [PERUBAHAN] z-index untuk konten teks === */
    .welcome-content {
        position: relative;
        z-index: 2; /* Pastikan konten teks di atas karakter */
    }

    .welcome-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #00d4ff;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    /* === [PERUBAHAN] Style kutipan agar tidak tumpang tindih === */
    .welcome-quote {
        background: rgba(0, 0, 0, 0.4); /* Background lebih solid */
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border-left: 3px solid #00d4ff;
        padding: 0.75rem 1rem;
        border-radius: 0 8px 8px 0;
        color: #cbd5e1;
        font-size: 0.875rem;
        font-style: italic;
        width: fit-content; /* Lebar sesuai konten */
        max-width: 70%; /* Batasi lebar di desktop */
        margin-top: 1rem; /* Jarak dari subtitle */
    }

    /* === [PERUBAHAN] z-index untuk karakter === */
    .character-image {
        position: absolute;
        right: 2rem;
        bottom: 0;
        height: 180px;
        filter: drop-shadow(0 4px 20px rgba(0, 212, 255, 0.3));
        animation: float 3s ease-in-out infinite;
        z-index: 1; /* Karakter di belakang welcome-content */
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Stats Cards */
    .stat-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }

    .stat-card:hover {
        background: rgba(0, 212, 255, 0.05);
        border-color: rgba(0, 212, 255, 0.3);
        transform: translateY(-4px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 212, 255, 0.3);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #00d4ff;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(0, 212, 255, 0.2);
    }

    .section-header i {
        font-size: 1.5rem;
        color: #00d4ff;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #e2e8f0;
        margin: 0;
    }

    /* Progress Bars */
    .progress-item {
        margin-bottom: 1.5rem;
    }

    .progress-item:last-child {
        margin-bottom: 0;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .progress-label {
        color: #cbd5e1;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .progress-label i {
        color: #00d4ff;
    }

    .progress-percentage {
        color: #00d4ff;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .progress {
        height: 10px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, #00d4ff 0%, #0099cc 100%);
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        transition: width 0.6s ease;
    }

    /* Widget Cards */
    .widget-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
    }

    .widget-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .widget-header i {
        color: #00d4ff;
        font-size: 1.25rem;
    }

    .widget-title {
        color: #94a3b8;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    /* Clock Widget */
    .clock-display {
        font-family: 'Courier New', monospace;
        font-size: 2.25rem;
        font-weight: 700;
        color: #00d4ff;
        letter-spacing: 2px;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
    }

    .date-display {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    /* Weather Widget */
    .weather-main {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .weather-icon {
        width: 60px;
        height: 60px;
        background: rgba(0, 212, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #00d4ff;
    }

    .weather-temp {
        font-size: 2.25rem;
        font-weight: 700;
        color: #e2e8f0;
    }

    .weather-description {
        color: #94a3b8;
        font-size: 0.95rem;
        text-transform: capitalize;
        margin-bottom: 0.75rem;
    }

    .weather-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.35rem;
    }

    .weather-detail i {
        color: #00d4ff;
        width: 16px;
    }

    /* Streak Widget */
    .streak-display {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .streak-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
    }

    .streak-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #e2e8f0;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .streak-label {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    /* Style untuk wrapper scroll Aktivitas Terbaru */
    .activity-list-wrapper {
        max-height: 280px; 
        overflow-y: auto;
        padding-right: 10px;
        margin-right: -10px;
    }

    /* Custom Scrollbar */
    .activity-list-wrapper::-webkit-scrollbar {
        width: 6px;
    }
    .activity-list-wrapper::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }
    .activity-list-wrapper::-webkit-scrollbar-thumb {
        background: rgba(0, 212, 255, 0.5);
        border-radius: 3px;
    }
    .activity-list-wrapper::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 212, 255, 0.8);
    }


    /* Activity Cards */
    .activity-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        background: rgba(0, 212, 255, 0.05);
        border-color: rgba(0, 212, 255, 0.2);
        transform: translateX(5px);
    }

    .activity-icon-wrapper {
        width: 44px;
        height: 44px;
        background: rgba(0, 212, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon-wrapper i {
        color: #00d4ff;
        font-size: 1.25rem;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        color: #e2e8f0;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-time {
        color: #64748b;
        font-size: 0.8rem;
    }

    .activity-xp {
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: #64748b;
    }

    .empty-state-title {
        color: #94a3b8;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Loading State */
    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        color: #64748b;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 2px;
        border-color: #00d4ff;
        border-right-color: transparent;
    }

    /* Error State */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        color: #fca5a5;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .character-image {
            height: 150px;
            right: 1rem;
        }

        .welcome-title {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 1rem 0;
        }

        .welcome-card {
            padding: 1.5rem;
        }

        /* === [PERUBAHAN] Posisi karakter di mobile === */
        .character-image {
            position: relative;
            right: auto;
            height: 120px;
            margin: 1rem auto 0;
            display: block;
            z-index: 0; /* Pastikan di bawah konten */
        }

        /* === [PERUBAHAN] Lebar kutipan di mobile === */
        .welcome-quote {
            max-width: 100%;
        }

        .welcome-title {
            font-size: 1.25rem;
        }

        .clock-display,
        .weather-temp,
        .streak-value {
            font-size: 1.75rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .activity-list-wrapper {
            max-height: 250px;
        }
    }
</style>
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
                    <img src="{{ asset('images/char.png') }}" alt="Character" class="character-image d-none d-md-block">
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <div class="stat-value">{{ $totalQuests ?? 0 }}</div>
                    <div class="stat-label">Total Quest</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="stat-value">{{ $completedQuests ?? 0 }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="stat-value">{{ $achievements ?? 0 }}</div>
                    <div class="stat-label">Achievements</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
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
                            <span class="progress-percentage">{{ $totalQuests > 0 ? round(($completedQuests / $totalQuests) * 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalQuests > 0 ? ($completedQuests / $totalQuests) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="bi bi-lightning-charge"></i>
                                Target XP Mingguan (500 XP)
                            </span>
                            <span class="progress-percentage">{{ $totalXP > 0 ? min(round(($totalXP / 500) * 100), 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalXP > 0 ? min(($totalXP / 500) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="bi bi-award"></i>
                                Pencapaian Terbuka (0/10)
                            </span>
                            <span class="progress-percentage">{{ $achievements > 0 ? min(round(($achievements / 10) * 100), 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
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
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            @foreach($recentActivities as $activity)
                            <div class="activity-card">
                                <div class="activity-icon-wrapper">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $activity->title }}</div>
                                    <div class="activity-time">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $activity->completed_at }}
                                    </div>
                                </div>
                                <div class="activity-xp">+{{ $activity->xp }} XP</div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="empty-state-title">Belum Ada Aktivitas</div>
                                <div class="empty-state-text">Mulai quest pertamamu sekarang!</div>
                            </div>
                        @endif
                    </div> </div>
            </div>
        </div>

        </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Motivational Quotes
    const quotes = [
        "Kesuksesan adalah hasil dari persiapan kecil yang dilakukan berulang kali.",
        "Setiap hari adalah kesempatan baru untuk menjadi lebih baik.",
        "Mulailah dari mana kamu berada, gunakan apa yang kamu punya.",
        "Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.",
        "Konsistensi adalah kunci kesuksesan.",
        "Jangan menunggu waktu yang tepat, ciptakan waktu itu.",
        "Perjalanan seribu mil dimulai dengan satu langkah.",
        "Kesuksesan bukan final, kegagalan bukan fatal.",
        "Masa depanmu diciptakan oleh apa yang kamu lakukan hari ini.",
        "Impian tidak bekerja kecuali kamu bekerja."
    ];

    // Set random quote on page load
    const quoteElement = document.getElementById('motivational-quote');
    if (quoteElement) {
        const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
        // Menggunakan innerHTML untuk mempertahankan Bootstrap Icon (bi-quote)
        quoteElement.innerHTML = `<i class="bi bi-quote me-2"></i> "${randomQuote}"`;
    }

    // Clock functionality
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockEl = document.getElementById('clock');
        const dateEl = document.getElementById('date');

        if(clockEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        if(dateEl) {
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    
    updateClock();
    setInterval(updateClock, 1000);

    // Weather functionality
    async function fetchWeather() {
        const weatherContent = document.getElementById('weather-content');
        
        try {
            // API Key telah dimasukkan
            const API_KEY = '93b7587a55f39ff4f0dc94e189ea5bd3'; 
            const city = 'Bekasi'; // Anda bisa mengubah kota ini
            
            const response = await fetch(
                `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&lang=id&appid=${API_KEY}`
            );
            
            if (!response.ok) {
                throw new Error('Gagal mengambil data cuaca');
            }
            
            const data = await response.json();
            
            // Icon mapping menggunakan Bootstrap Icons
            const weatherIcons = {
                'Clear': 'bi-brightness-high-fill',
                'Clouds': 'bi-cloud-fill',
                'Rain': 'bi-cloud-rain-heavy-fill',
                'Drizzle': 'bi-cloud-drizzle-fill',
                'Thunderstorm': 'bi-cloud-lightning-rain-fill',
                'Snow': 'bi-cloud-snow-fill',
                'Mist': 'bi-cloud-fog2-fill',
                'Fog': 'bi-cloud-fog2-fill'
            };
            
            const iconClass = weatherIcons[data.weather[0].main] || 'bi-cloud-sun-fill';
            
            // Render HTML baru yang sesuai dengan widget cuaca
            weatherContent.innerHTML = `
                <div class="weather-main">
                    <div class="weather-icon">
                        <i class="bi ${iconClass}"></i>
                    </div>
                    <div class="weather-temp">${Math.round(data.main.temp)}°C</div>
                </div>
                <div class="weather-description">${data.weather[0].description}</div>
                <div class="weather-detail">
                    <i class="bi bi-droplet-half"></i>
                    <span>Kelembaban: ${data.main.humidity}%</span>
                </div>
                <div class="weather-detail">
                    <i class="bi bi-wind"></i>
                    <span>Angin: ${Math.round(data.wind.speed * 3.6)} km/h</span>
                </div>
            `;
        } catch (error) {
            // Render HTML error yang sesuai
            weatherContent.innerHTML = `
                <div class="error-message">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <span>Tidak dapat memuat data cuaca.</span>
                </div>
            `;
            console.error('Weather API Error:', error);
        }
    }
    
    // Panggil fungsi-fungsi
    fetchWeather();
    setInterval(fetchWeather, 600000); // Refresh cuaca setiap 10 menit
</script>
@endpush