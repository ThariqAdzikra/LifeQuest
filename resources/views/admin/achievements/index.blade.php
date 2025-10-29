@extends('layouts.app')

@section('content')
<div class="quest-board-container" style="max-width: 1140px; margin: 0 auto; padding: 2rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div class="section-header" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
            <i class="bi bi-award-fill" style="font-size: 1.2rem; color: #00d4ff;"></i>
            <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
                Kelola Achievements
            </h2>
        </div>
        <a href="{{ route('admin.achievements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
    </div>

    @include('partials.admin_alerts') {{-- Include partials untuk notifikasi --}}

    <div class="glass-card leaderboard-list" style="padding: 0;"> {{-- Hapus padding default --}}
        {{-- Header Tabel --}}
        <div class="leaderboard-header" style="grid-template-columns: 80px 1fr 2fr 150px; padding: 1rem 2rem;"> {{-- Sesuaikan kolom --}}
            <div class="header-icon">Ikon</div>
            <div class="header-title">Judul</div>
            <div class="header-desc">Deskripsi</div>
            <div class="header-actions">Aksi</div>
        </div>

        {{-- Body Tabel --}}
        @forelse ($achievements as $achievement)
            <div class="leaderboard-row" style="grid-template-columns: 80px 1fr 2fr 150px; padding: 1rem 2rem; gap: 1rem;"> {{-- Sesuaikan kolom --}}
                <div class="icon-col">
                    @if($achievement->icon_path)
                        <img src="{{ Storage::url($achievement->icon_path) }}" alt="Icon" style="width: 40px; height: 40px; border-radius: 8px; object-fit: contain;">
                    @else
                        <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color: #94a3b8;">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                <div class="title-col" style="color: #e2e8f0; font-weight: 600;">
                    {{ $achievement->title }}
                </div>
                <div class="desc-col" style="color: #94a3b8; font-size: 0.9rem;">
                    {{ $achievement->description ?? '-' }}
                </div>
                <div class="actions-col" style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.achievements.edit', $achievement) }}" class="btn btn-sm btn-warning" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('admin.achievements.destroy', $achievement) }}" method="POST" onsubmit="return confirm('Yakin hapus achievement ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                Belum ada achievement yang dibuat.
            </div>
        @endforelse
    </div>

    <div class="pagination-links" style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $achievements->links() }}
    </div>
</div>
@endsection