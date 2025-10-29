<nav>
    <div class="nav-container">
        <div class="nav-left">
            @auth
                <a href="{{ route('dashboard') }}" class="logo">LifeQuest</a>
            @else
                <a href="{{ route('landing') }}" class="logo">LifeQuest</a>
            @endauth
        </div>

        <div class="nav-menu">
            @auth
                {{-- --- MODIFIKASI DIMULAI DISINI (DESKTOP) --- --}}
                @if (Auth::user()->isAdmin())
                    {{-- HANYA TAMPILKAN INI JIKA ADMIN --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                       style="color: #00d4ff; font-weight: 600;">
                        <i class="bi bi-shield-lock-fill"></i> Admin Panel
                    </a>
                @else
                    {{-- TAMPILKAN INI SEMUA JIKA USER BIASA --}}
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('quests.index') }}" class="{{ request()->routeIs('quests.*') ? 'active' : '' }}">Quest Saya</a>
                    <a href="{{ route('achievements.index') }}" class="{{ request()->routeIs('achievements.*') ? 'active' : '' }}">Achievements</a>
                    <a href="{{ route('leaderboard') }}" class="{{ request()->routeIs('leaderboard') ? 'active' : '' }}">Leaderboard</a>
                @endif
                {{-- --- MODIFIKASI SELESAI (DESKTOP) --- --}}
            @endauth
        </div>

        <div class="nav-auth">
            @auth
                <div class="profile-dropdown">
                    <button onclick="toggleDropdown()" class="profile-trigger" type="button">
                        <span class="profile-name">{{ Auth::user()->name }}</span>
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div class="profile-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </button>
                    
                    <div id="profileDropdown" class="dropdown-menu" style="display: none;">
                         <div style="padding: 0.75rem 1.5rem; border-bottom: 1px solid rgba(0, 212, 255, 0.2); margin-bottom: 0.5rem;">
                             <div style="font-weight: 600; color: #00d4ff;">{{ Auth::user()->name }}</div>
                             <div style="font-size: 0.8rem; color: #b0b0c0;">{{ Auth::user()->email }}</div>
                         </div>
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="display: flex; align-items: center; gap: 0.5rem; font-family: 'Poppins', sans-serif;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
                <a href="{{ route('register') }}" class="register-link">Register</a>
            @endauth

            <button class="mobile-toggle" onclick="toggleMobileMenu()">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <div id="mobileMenu" class="mobile-menu-container">
        @auth
            <div class="profile-info">
                <div class="mobile-avatar-container">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="mobile-avatar-img">
                    @else
                        <div class="mobile-avatar-initials">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div class="mobile-avatar-text">
                     <div>{{ Auth::user()->name }}</div>
                     <div>{{ Auth::user()->email }}</div>
                </div>
            </div>

            {{-- --- MODIFIKASI DIMULAI DISINI (MOBILE) --- --}}
            @if (Auth::user()->isAdmin())
                {{-- HANYA TAMPILKAN INI JIKA ADMIN --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                   style="color: #00d4ff; font-weight: 600; background: rgba(0, 212, 255, 0.1);">
                    <i class="bi bi-shield-lock-fill"></i> Admin Panel
                </a>
            @else
                {{-- TAMPILKAN INI SEMUA JIKA USER BIASA --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a>
                <a href="{{ route('quests.index') }}" class="{{ request()->routeIs('quests.*') ? 'active' : '' }}">Quest Saya</a>
                <a href="{{ route('achievements.index') }}" class="{{ request()->routeIs('achievements.*') ? 'active' : '' }}">Achievements</a>
                <a href="{{ route('leaderboard') }}" class="{{ request()->routeIs('leaderboard') ? 'active' : '' }}">Leaderboard</a>
            @endif
            {{-- --- MODIFIKASI SELESAI (MOBILE) --- --}}

            {{-- Ini link umum, biarkan tampil untuk admin dan user --}}
            <a href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="login-link" style="border: none; background: rgba(0, 212, 255, 0.1); color: #00d4ff; margin: 0.5rem 2rem;">Login</a>
            <a href="{{ route('register') }}" class="register-link" style="border: none; margin: 0.5rem 2rem; text-align: center;">Register</a>
        @endauth
    </div>


    <script src="{{ asset('js/nav/main.js') }}" defer></script>
</nav>