<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LifeQuest - Jejak Kebiasaan Positif Anda')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- ▼▼▼ INI ADALAH PERBAIKANNYA ▼▼▼ --}}
    {{-- Memuat file CSS Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- ▲▲▲ AKHIR DARI PERBAIKAN ▲▲▲ --}}

    {{-- BARU: Link ke file CSS eksternal Anda (Harus setelah Bootstrap) --}}
    <link rel="stylesheet" href="{{ asset('css/app/style.css') }}">
    
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

    {{-- BARU: Link ke file JS eksternal Anda --}}
    <script src="{{ asset('js/app/main.js') }}" defer></script>
    
   <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
</body>
</html>