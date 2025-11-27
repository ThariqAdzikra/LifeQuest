<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LifeQuest - Jejak Kebiasaan Positif Anda')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Memuat file CSS Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Link ke file CSS eksternal Anda (Harus setelah Bootstrap) --}}
    <link rel="stylesheet" href="{{ asset('css/app/style.css') }}">
    
    {{-- --- [TAMBAHAN BARU] Link ke CSS Navigasi Sesuai Permintaan --- --}}
    <link rel="stylesheet" href="{{ asset('css/nav/style.css') }}">
    {{-- --- [AKHIR TAMBAHAN] --- --}}

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
            
            <div class="footer-social">
                <a href="https://www.facebook.com/BahlilLahadaliaOfficial/?locale=id_ID" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/thrqdz_/" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.linkedin.com/in/muhammad-thariq-adzikra-6b3b7221b/" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="https://github.com/ThariqAdzikra" title="GitHub"><i class="bi bi-github"></i></a>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} LifeQuest. Semua hak dilindungi. | Dibuat dengan ⚡ untuk warrior sejati</p>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            
            <div class="modal-content glass-card">
                
                <div class="modal-header">
                    <h5 class="modal-title page-title" id="submissionModalLabel">Kirim Bukti Quest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #fff;"></button>
                </div>
                
                <form id="submissionForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label for="submission_file">Upload Bukti (Maks: 5MB)</label>
                            <input type="file" id="submission_file" name="submission_file" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="submission_notes">Catatan (Opsional)</label>
                            <textarea id="submission_notes" name="submission_notes" rows="3" class="form-control" placeholder="Tulis catatan untuk admin di sini..."></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- --- AKHIR BLOK MODAL --- --}}
    
   <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
</body>
</html>