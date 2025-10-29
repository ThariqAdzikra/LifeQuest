{{-- resources/views/partials/admin_alerts.blade.php --}}

{{-- Tampilkan pesan sukses jika ada di session --}}
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        {{-- Ikon centang --}}
        <i class="bi bi-check-circle-fill" style="font-size: 1.2rem;"></i> 
        {{-- Teks pesan --}}
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- Tampilkan pesan error jika ada di session --}}
@if(session('error'))
    <div style="background: rgba(220, 38, 38, 0.15); border: 1px solid rgba(220, 38, 38, 0.4); color: #f87171; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        {{-- Ikon peringatan --}}
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem;"></i> 
        {{-- Teks pesan --}}
        <span>{{ session('error') }}</span>
    </div>
@endif