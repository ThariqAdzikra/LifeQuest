<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HabitQuest - Jejak Kebiasaan Positif Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1a2e 100%);
            color: #e0e0e0;
            overflow-x: hidden;
        }

        /* Navbar Glass Effect */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(10px);
            background: rgba(15, 20, 50, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 3rem;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: #e0e0e0;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: #00d4ff;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00d4ff, #a78bfa);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-auth {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-auth a {
            color: #e0e0e0;
            text-decoration: none;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .login-link {
            color: #00d4ff;
            border: 1px solid #00d4ff;
        }

        .login-link:hover {
            background: rgba(0, 212, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }

        .register-link {
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            color: #0a0e27;
            font-weight: 600;
        }

        .register-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
        }

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

        /* Footer */
        footer {
            background: linear-gradient(135deg, #0a0e27, #0f1432);
            border-top: 1px solid rgba(0, 212, 255, 0.2);
            padding: 3rem 2rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #b0b0c0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #00d4ff;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .footer-bottom {
            color: #707080;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
        }

        .footer-logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                gap: 1rem;
                display: none;
            }

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

            .logo {
                font-size: 1.3rem;
            }

            .nav-auth {
                gap: 0.5rem;
            }

            .nav-auth a {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-container">
            <div class="nav-left">
                <a href="{{ route('landing') }}" class="logo">⚔️ HABITQUEST</a>
                <ul class="nav-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#features">Fitur</a></li>
                </ul>
            </div>
            <div class="nav-auth">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn" style="background: linear-gradient(135deg, #00d4ff, #a78bfa); color: #0a0e27;">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="login-link" style="background: none; border: none; cursor: pointer;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-link">Login</a>
                    <a href="{{ route('register') }}" class="register-link">Register</a>
                @endauth
            </div>
        </div>
    </nav>

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

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">⚔️ HABITQUEST</div>
            <p style="margin-bottom: 2rem; color: #b0b0c0;">Bergabunglah dengan ribuan warrior dalam misi mengubah hidup mereka</p>
            <div class="footer-links">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
                <a href="#">Blog</a>
                <a href="#">Dukungan</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 HabitQuest. Semua hak dilindungi. | Dibuat dengan ⚡ untuk warrior sejati</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll untuk navbar links
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });

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
</body>
</html>