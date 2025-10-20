@extends('layouts.app')

@section('title', 'Dashboard - LifeQuest')

@push('styles')
<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .welcome-section {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
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

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        color: #b0b0c0;
        font-size: 1.1rem;
    }

    .widgets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .widget-card {
        background: linear-gradient(135deg, rgba(15, 20, 50, 0.8), rgba(26, 26, 46, 0.8));
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .widget-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 212, 255, 0.3);
        box-shadow: 0 10px 30px rgba(0, 212, 255, 0.2);
    }

    .widget-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .widget-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .widget-title {
        font-size: 1rem;
        font-weight: 600;
        color: #e0e0e0;
    }

    .widget-content {
        color: #b0b0c0;
    }

    .clock-display {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #00d4ff;
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        margin-bottom: 0.5rem;
    }

    .date-display {
        font-size: 1rem;
        color: #b0b0c0;
    }

    .weather-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .weather-temp {
        font-size: 3rem;
        font-weight: 700;
        color: #00d4ff;
    }

    .weather-icon {
        font-size: 3rem;
    }

    .weather-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .weather-detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(167, 139, 250, 0.05));
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: rgba(0, 212, 255, 0.3);
        transform: translateY(-3px);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #b0b0c0;
        font-size: 0.95rem;
    }

    .loading {
        text-align: center;
        color: #b0b0c0;
        padding: 1rem;
    }

    .error {
        color: #ff6b6b;
        text-align: center;
        padding: 1rem;
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}! 🎯</h1>
            <p class="welcome-subtitle">Siap menaklukkan quest hari ini? Mari mulai perjalanan epic-mu!</p>
        </div>
    </div>

    <!-- Widgets Grid -->
    <div class="widgets-grid">
        <!-- Clock Widget -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-icon">🕐</div>
                <h3 class="widget-title">Waktu Real-time</h3>
            </div>
            <div class="widget-content">
                <div class="clock-display" id="clock">--:--:--</div>
                <div class="date-display" id="date">Loading...</div>
            </div>
        </div>

        <!-- Weather Widget -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-icon">🌤️</div>
                <h3 class="widget-title">Cuaca Hari Ini</h3>
            </div>
            <div class="widget-content" id="weather-content">
                <div class="loading">Memuat data cuaca...</div>
            </div>
        </div>

        <!-- Quick Stats Widget -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-icon">⚡</div>
                <h3 class="widget-title">Streak Anda</h3>
            </div>
            <div class="widget-content">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🔥</div>
                    <div class="stat-value">{{ $currentStreak ?? 0 }}</div>
                    <div class="stat-label">Hari Berturut-turut</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totalQuests ?? 0 }}</div>
            <div class="stat-label">Total Quest</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $completedQuests ?? 0 }}</div>
            <div class="stat-label">Quest Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $achievements ?? 0 }}</div>
            <div class="stat-label">Achievements Terbuka</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalXP ?? 0 }}</div>
            <div class="stat-label">Total XP</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Clock functionality
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('date').textContent = now.toLocaleDateString('id-ID', options);
    }
    
    updateClock();
    setInterval(updateClock, 1000);

    // Weather functionality
    async function fetchWeather() {
        const weatherContent = document.getElementById('weather-content');
        
        try {
            // Gunakan API OpenWeatherMap (Anda perlu mendaftar untuk mendapatkan API key gratis)
            const API_KEY = 'YOUR_OPENWEATHER_API_KEY'; // Ganti dengan API key Anda
            const city = 'Bekasi'; // Sesuaikan dengan lokasi pengguna
            
            const response = await fetch(
                `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&lang=id&appid=${API_KEY}`
            );
            
            if (!response.ok) {
                throw new Error('Gagal mengambil data cuaca');
            }
            
            const data = await response.json();
            
            const weatherIcons = {
                'Clear': '☀️',
                'Clouds': '☁️',
                'Rain': '🌧️',
                'Drizzle': '🌦️',
                'Thunderstorm': '⛈️',
                'Snow': '❄️',
                'Mist': '🌫️',
                'Fog': '🌫️'
            };
            
            const icon = weatherIcons[data.weather[0].main] || '🌤️';
            
            weatherContent.innerHTML = `
                <div class="weather-main">
                    <div>
                        <div class="weather-temp">${Math.round(data.main.temp)}°C</div>
                        <div style="color: #b0b0c0; text-transform: capitalize;">${data.weather[0].description}</div>
                    </div>
                    <div class="weather-icon">${icon}</div>
                </div>
                <div class="weather-details">
                    <div class="weather-detail-item">
                        <span>💧</span>
                        <span>Kelembaban: ${data.main.humidity}%</span>
                    </div>
                    <div class="weather-detail-item">
                        <span>💨</span>
                        <span>Angin: ${Math.round(data.wind.speed * 3.6)} km/h</span>
                    </div>
                    <div class="weather-detail-item">
                        <span>🌡️</span>
                        <span>Terasa: ${Math.round(data.main.feels_like)}°C</span>
                    </div>
                    <div class="weather-detail-item">
                        <span>📍</span>
                        <span>${data.name}</span>
                    </div>
                </div>
            `;
        } catch (error) {
            weatherContent.innerHTML = `
                <div class="error">
                    <p>Tidak dapat memuat data cuaca</p>
                    <p style="font-size: 0.85rem; margin-top: 0.5rem;">Pastikan API key sudah dikonfigurasi</p>
                </div>
            `;
            console.error('Weather API Error:', error);
        }
    }
    
    // Fetch weather on page load
    fetchWeather();
    
    // Update weather every 10 minutes
    setInterval(fetchWeather, 600000);
</script>
@endpush