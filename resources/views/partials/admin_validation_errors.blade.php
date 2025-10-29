{{-- resources/views/partials/admin_validation_errors.blade.php --}}

{{-- Tampilkan blok ini HANYA jika ada error validasi --}}
@if ($errors->any())
    <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #f59e0b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{-- Judul blok error --}}
        <strong style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <i class="bi bi-exclamation-circle-fill" style="font-size: 1.1rem;"></i> 
            Oops! Ada beberapa kesalahan:
        </strong>
        {{-- Daftar error --}}
        <ul style="margin-top: 0.5rem; padding-left: 1.5rem; margin-bottom: 0; list-style-type: disc;">
            {{-- Loop melalui semua pesan error --}}
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif