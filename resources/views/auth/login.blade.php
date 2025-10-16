<x-guest-layout>
    <h2 class="auth-title">🎮 LOGIN WARRIOR</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="warrior@habitquest.com">
            @error('email')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
            @error('password')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-group">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" class="checkbox-label">Ingat saya</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
            🚀 Masuk ke Quest
        </button>

        <!-- Footer Links -->
        <div class="form-footer">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    Lupa password Anda?
                </a>
            @endif
        </div>

        <div class="divider">
            <span>atau</span>
        </div>

        <div class="form-footer">
            <span style="color: #b0b0c0; font-size: 0.9rem;">Belum punya akun?</span>
            <a href="{{ route('register') }}" style="margin-left: 0.5rem;">
                Daftar Sekarang →
            </a>
        </div>
    </form>
</x-guest-layout>