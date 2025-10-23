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

                <script>
                    function toggleDropdown() {
                        const dropdown = document.getElementById('profileDropdown');
                        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                    }

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        const dropdown = document.getElementById('profileDropdown');
                        const trigger = document.querySelector('.profile-trigger');
                        
                        if (dropdown && trigger) {
                            if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
                                dropdown.style.display = 'none';
                            }
                        }
                    });

                    // Prevent dropdown from closing when clicking inside it
                    document.addEventListener('DOMContentLoaded', function() {
                        const dropdown = document.getElementById('profileDropdown');
                        if (dropdown) {
                            dropdown.addEventListener('click', function(event) {
                                event.stopPropagation();
                            });
                        }
                    });
                </script>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
                <a href="{{ route('register') }}" class="register-link">Register</a>
            @endauth
        </div>
    </div>
</nav>