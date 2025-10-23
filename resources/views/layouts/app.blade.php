<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LifeQuest - Jejak Kebiasaan Positif Anda')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
            min-height: 100vh;
        }

        /* --- STYLE UNTUK NAVBAR --- */
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
            max-width: 1400px;
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
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: #e0e0e0;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu a:hover {
            color: #00d4ff;
            background: rgba(0, 212, 255, 0.1);
        }

        .nav-menu a.active {
            color: #00d4ff;
            background: rgba(0, 212, 255, 0.15);
        }

        .nav-menu a.active::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #00d4ff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
        }

        .nav-auth {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-auth a, .nav-auth button {
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

        .profile-dropdown {
            position: relative;
        }
        
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem; 
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.25rem 0.75rem 0.25rem 0.25rem; 
            border-radius: 9999px; 
            transition: background-color 0.3s ease;
        }

        .profile-trigger:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .profile-name {
            color: #e0e0e0;
            font-weight: 500;
            font-size: 0.9rem;
            margin-left: 0.5rem;
            transition: color 0.3s ease;
        }

        .profile-trigger:hover .profile-name {
            color: #00d4ff;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            color: #0a0e27;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            flex-shrink: 0; 
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 120%;
            width: 220px;
            background: linear-gradient(135deg, rgba(10, 14, 39, 0.95), rgba(15, 20, 50, 0.95));
            border: 1px solid rgba(0, 212, 255, 0.3);
            backdrop-filter: blur(15px);
            border-radius: 12px;
            padding: 0.5rem 0;
            z-index: 10;
            box-shadow: 0 10px 40px rgba(0, 212, 255, 0.2), 0 0 20px rgba(0, 212, 255, 0.1);
        }

        .dropdown-menu a, .dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            color: #e0e0e0;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .dropdown-menu a:hover, .dropdown-menu button:hover {
            background-color: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
        }
        
        .dropdown-menu a i, .dropdown-menu button i {
            font-size: 1rem;
        }

        .dropdown-menu form {
            margin: 0;
        }
        /* --- AKHIR STYLE NAVBAR --- */


        main {
            margin-top: 80px; 
            min-height: calc(100vh - 80px - 250px); 
        }
        
        /* --- STYLE FOOTER --- */
        footer {
            background: linear-gradient(135deg, #0a0e27, #0f1432);
            border-top: 1px solid rgba(0, 212, 255, 0.2);
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .footer-content { max-width: 1200px; margin: 0 auto; }
        .footer-logo { 
            font-family: 'Orbitron', sans-serif; 
            font-size: 1.8rem; 
            margin-bottom: 1rem; 
            background: linear-gradient(135deg, #00d4ff, #a78bfa); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
        }
        .footer-tagline { 
            margin-bottom: 2.5rem; 
            color: #b0b0c0; 
            font-size: 1.05rem;
            font-style: italic;
        }
        
        .footer-links { 
            display: flex; 
            justify-content: center; 
            gap: 2.5rem; 
            margin-bottom: 2.5rem; 
            flex-wrap: wrap; 
        }
        .footer-links a { 
            color: #b0b0c0; 
            text-decoration: none; 
            transition: all 0.3s ease; 
            font-weight: 500; 
        }
        .footer-links a:hover { 
            color: #00d4ff; 
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5); 
        }
        
        .footer-social { 
            display: flex; 
            justify-content: center; 
            gap: 1.5rem; 
            margin-bottom: 2.5rem; 
        }
        .footer-social a { 
            color: #b0b0c0; 
            text-decoration: none; 
            font-size: 1.5rem; 
            transition: all 0.3s ease; 
        }
        .footer-social a:hover { 
            color: #00d4ff; 
            transform: scale(1.1) translateY(-3px); 
        }

        .footer-bottom { 
            color: #707080; 
            font-size: 0.9rem; 
            border-top: 1px solid rgba(255, 255, 255, 0.1); 
            padding-top: 2rem; 
        }
        /* --- AKHIR STYLE FOOTER --- */

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            main {
                margin-top: 70px;
            }
            footer {
                padding: 3rem 1rem;
            }
            .footer-links {
                gap: 1.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    
    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">LifeQuest</div>
            <p class="footer-tagline">"Ubah kebiasaan kecil menjadi pencapaian besar. Mulai petualanganmu hari ini."</p>
            
            <div class="footer-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('quests.index') }}">Quest Saya</a>
                <a href="{{ route('profile.edit') }}">Profile</a>
                <a href="#">Blog</a>
                <a href="#">Dukungan</a>
            </div>

            <div class="footer-social">
                <a href="#" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" title="Discord"><i class="bi bi-discord"></i></a>
                <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} LifeQuest. Semua hak dilindungi. | Dibuat dengan ⚡ untuk warrior sejati</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
</body>
</html>