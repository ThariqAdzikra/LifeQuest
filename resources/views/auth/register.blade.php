<x-guest-layout>
    <h2 class="auth-title">⚔️ DAFTAR WARRIOR BARU</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Nama Warrior</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda">
            @error('name')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="warrior@habitquest.com">
            @error('email')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            @error('password')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda">
            @error('password_confirmation')
                <div class="error-message">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
            🎮 Mulai Petualangan
        </button>

        <!-- Footer Links -->
        <div class="divider">
            <span>atau</span>
        </div>

        <div class="form-footer">
            <span style="color: #b0b0c0; font-size: 0.9rem;">Sudah punya akun?</span>
            <a href="{{ route('login') }}" style="margin-left: 0.5rem;">
                Login Sekarang →
            </a>
        </div>
    </form>
</x-guest-layout>