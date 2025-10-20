<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LifeQuest - Jejak Kebiasaan Positif Anda')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.25rem;
            border-radius: 9999px;
            transition: background-color 0.3s ease;
        }

        .profile-trigger:hover {
            background-color: rgba(255, 255, 255, 0.1);
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
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 120%;
            width: 200px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(167, 139, 250, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 0.5rem 0;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .dropdown-menu a, .dropdown-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            color: #e0e0e0;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
        }

        .dropdown-menu a:hover, .dropdown-menu button:hover {
            background-color: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
        }

        main {
            margin-top: 80px;
            min-height: calc(100vh - 80px - 200px);
        }
        
        footer {
            background: linear-gradient(135deg, #0a0e27, #0f1432);
            border-top: 1px solid rgba(0, 212, 255, 0.2);
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .footer-content { max-width: 1200px; margin: 0 auto; }
        .footer-logo { font-family: 'Orbitron', sans-serif; font-size: 1.5rem; margin-bottom: 1rem; background: linear-gradient(135deg, #00d4ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .footer-links { display: flex; justify-content: center; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .footer-links a { color: #b0b0c0; text-decoration: none; transition: all 0.3s ease; }
        .footer-links a:hover { color: #00d4ff; text-shadow: 0 0 10px rgba(0, 212, 255, 0.5); }
        .footer-bottom { color: #707080; font-size: 0.9rem; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 2rem; }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="nav-left">
                @auth
                    <a href="{{ route('dashboard') }}" class="logo">LifeQuest</a>
                    <div class="nav-menu">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('quests.index') }}" class="{{ request()->routeIs('quests.*') ? 'active' : '' }}">Quest Saya</a>
                        <a href="{{ route('achievements.index') }}" class="{{ request()->routeIs('achievements.*') ? 'active' : '' }}">Achievements</a>
                        <a href="{{ route('leaderboard') }}" class="{{ request()->routeIs('leaderboard') ? 'active' : '' }}">Leaderboard</a>
                    </div>
                @else
                    <a href="{{ route('landing') }}" class="logo">LifeQuest</a>
                @endauth
            </div>
            <div class="nav-auth">
                @auth
                    <div x-data="{ open: false }" class="profile-dropdown">
                        <button @click="open = !open" class="profile-trigger">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div class="profile-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </button>

                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="dropdown-menu"
                             style="display: none;">
                            
                             <div style="padding: 0.75rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 0.5rem;">
                                 <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                                 <div style="font-size: 0.8rem; color: #b0b0c0;">{{ Auth::user()->email }}</div>
                             </div>

                            <a href="{{ route('profile.edit') }}">Profile</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="login-link">Login</a>
                    <a href="{{ route('register') }}" class="register-link">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">LifeQuest</div>
            <p style="margin-bottom: 2rem; color: #b0b0c0;">Bergabunglah dengan ribuan warrior dalam misi mengubah hidup mereka</p>
            <div class="footer-links">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
                <a href="#">Blog</a>
                <a href="#">Dukungan</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 LifeQuest. Semua hak dilindungi. | Dibuat dengan ⚡ untuk warrior sejati</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>