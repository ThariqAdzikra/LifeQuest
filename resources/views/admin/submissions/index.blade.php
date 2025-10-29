@extends('layouts.app')

@section('content')
<div class="quest-board-container" style="max-width: 1140px; margin: 0 auto; padding: 2rem;">

    <div class="section-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 0.75rem;">
        <i class="bi bi-clipboard-check-fill" style="font-size: 1.2rem; color: #00d4ff;"></i>
        <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
            Review Submission Quest
        </h2>
    </div>

    {{-- Tampilkan notifikasi sukses/error --}}
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(220, 38, 38, 0.15); border: 1px solid rgba(220, 38, 38, 0.4); color: #f87171; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Daftar Submission --}}
    @forelse ($submissions as $log)
    <div class="quest-card glass-card" style="padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        
        <div class="quest-info" style="flex-grow: 1; min-width: 300px;">
            <h3 style="font-family: 'Orbitron', sans-serif; font-size: 1.3rem; margin-bottom: 0.5rem; color: #e2e8f0; letter-spacing: 1px;">
                {{ $log->quest->title }}
            </h3>
            
            <div class="quest-meta" style="display: flex; flex-wrap: wrap; gap: 1.5rem; font-size: 0.9rem; color: #94a3b8; margin-bottom: 1rem;">
                <span><i class="bi bi-person-fill" style="color: #00d4ff;"></i> User: {{ $log->user->name }}</span>
                <span><i class="bi bi-calendar-event" style="color: #00d4ff;"></i> Dikirim: {{ $log->updated_at->format('d F Y, H:i') }}</span>
            </div>
            
            <p style="color: #cbd5e1; background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <strong>Catatan User:</strong><br>
                {{ $log->submission_notes ?? '(Tidak ada catatan)' }}
            </p>
            
            {{-- Link untuk melihat file bukti --}}
            @if($log->submission_file_path)
            <a href="{{ Storage::url($log->submission_file_path) }}" target="_blank" class="btn btn-primary" style="margin-top: 1rem; padding: 0.5rem 1rem; font-size: 0.9rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #00d4ff; color: #0a0e27; box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);">
                <i class="bi bi-eye-fill"></i> Lihat Bukti
            </a>
            @endif
        </div>
        
        {{-- Tombol Aksi Admin --}}
        <div class="quest-actions" style="display: flex; flex-direction: column; gap: 0.75rem; min-width: 160px; flex-shrink: 0; margin-left: auto;">
            
            {{-- TOMBOL APPROVE --}}
            <form action="{{ route('admin.submissions.approve', $log->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" style="width: 100%; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4);">
                    <i class="bi bi-check-lg"></i> Setujui
                </button>
            </form>
            
            {{-- TOMBOL REJECT --}}
            <form action="{{ route('admin.submissions.reject', $log->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak submission ini?');">
                @csrf
                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(220, 38, 38, 0.15); color: #f87171; border: 1px solid rgba(220, 38, 38, 0.4);">
                    <i class="bi bi-x-lg"></i> Tolak
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="empty-state-text" style="color: #94a3b8; text-align: center; padding: 2rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        Tidak ada submission yang menunggu review.
    </p>
    @endforelse

    {{-- Link Paginasi --}}
    <div class="pagination-links" style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $submissions->links() }}
    </div>
</div>
@endsection