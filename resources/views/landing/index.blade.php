@extends('layouts.app')

@section('title', 'HabitQuest - Jejak Kebiasaan Positif Anda')

@push('styles')
<style>
    /* Hero Section */
    .hero {
        margin-top: 70px;
        height: 100vh;
        background: linear-gradient(rgba(10, 14, 39, 0.6), rgba(26, 26, 46, 0.7)),
                    url('{{ asset("images/heroLanding.jpg") }}') center/cover;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(167, 139, 250, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-content {
        text-align: center;
        z-index: 10;
        max-width: 700px;
        animation: fadeInUp 1s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        background: linear-gradient(135deg, #00d4ff, #a78bfa, #ff006e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        color: #b0b0c0;
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 1rem 2.5rem;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        color: #0a0e27;
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 212, 255, 0.6);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #e0e0e0;
        border: 2px solid rgba(0, 212, 255, 0.5);
        backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
        background: rgba(0, 212, 255, 0.2);
        border-color: #00d4ff;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }

    /* Features Section */
    .features {
        padding: 6rem 2rem;
        background: linear-gradient(135deg, #0f1432 0%, #1a1a2e 100%);
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        background: linear-gradient(135deg, #00d4ff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 12px;
        padding: 2rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent, rgba(0, 212, 255, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: #00d4ff;
        box-shadow: 0 15px 40px rgba(0, 212, 255, 0.2);
    }

    .feature-card:hover::before {
        opacity: 1;
    }

    .feature-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: #00d4ff;
        letter-spacing: 1px;
    }

    .feature-card p {
        color: #b0b0c0;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Scroll Animation */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2.5rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            max-width: 300px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>MULAI PETUALANGAN ANDA</h1>
            <p>Raih kebiasaan positif dan naik level dalam kehidupan. Setiap hari adalah misi baru untuk menjadi versi terbaik dari diri Anda!</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">🎮 Mulai Sekarang</a>
                <a href="#features" class="btn btn-secondary">📖 Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">FITUR UTAMA QUEST</h2>
            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">📊</div>
                    <h3>Tracking Progres</h3>
                    <p>Pantau perkembangan kebiasaan positif Anda dengan dashboard yang intuitif dan real-time analytics.</p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">🏆</div>
                    <h3>Sistem Reward</h3>
                    <p>Dapatkan poin, badge, dan achievement untuk setiap kebiasaan yang Anda selesaikan setiap hari.</p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">🎯</div>
                    <h3>Target Harian</h3>
                    <p>Tetapkan target kebiasaan harian Anda dan lihat berapa persen progres Anda dalam mencapainya.</p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">⚡</div>
                    <h3>Streak Tracker</h3>
                    <p>Jangan putus streak Anda! Lihat berapa hari berturut-turut Anda telah menjalankan kebiasaan positif.</p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">👥</div>
                    <h3>Komunitas</h3>
                    <p>Bergabunglah dengan komunitas warrior lainnya dan saling mendukung dalam perjalanan transformasi diri.</p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">📈</div>
                    <h3>Analitik Mendalam</h3>
                    <p>Dapatkan insight lengkap tentang pola kebiasaan Anda dengan grafik dan statistik yang komprehensif.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Fade in animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
</script>
@endpush
