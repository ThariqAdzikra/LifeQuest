@extends('layouts.app')

@section('content')
<div class="quest-board-container" style="max-width: 1140px; margin: 0 auto; padding: 2rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        {{-- Header --}}
        <div class="section-header" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
            <i class="bi bi-shield-check" style="font-size: 1.2rem; color: #00d4ff;"></i>
            <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
                Kelola Quest Admin
            </h2>
        </div>
        
        {{-- Tombol 'Buat Baru' --}}
        <a href="{{ route('admin.quests.create') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #00d4ff; color: #0a0e27; box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);">
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
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

    {{-- Daftar Quest (menggunakan style quest-card Anda) --}}
    @forelse ($adminQuests as $quest)
    <div class="quest-card glass-card" style="padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        <div class="quest-info" style="flex-grow: 1; min-width: 300px;">
            <h3 style="font-family: 'Orbitron', sans-serif; font-size: 1.3rem; margin-bottom: 0.5rem; color: #e2e8f0; letter-spacing: 1px;">
                {{ $quest->title }}
            </h3>
            
            <div class="quest-meta" style="display: flex; flex-wrap: wrap; gap: 1.5rem; font-size: 0.9rem; color: #94a3b8; margin-bottom: 1rem;">
                <span><i class="bi bi-clock" style="color: #00d4ff;"></i> Frekuensi: {{ ucfirst($quest->frequency) }}</span>
                <span><i class="bi bi-bar-chart-line" style="color: #00d4ff;"></i> Kesulitan: {{ ucfirst($quest->difficulty) }}</span>
            </div>
            
            <div class="quest-rewards" style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
                <span class="reward-tag" style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: flex; align-items: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.7); padding: 0.25rem 0.75rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);"><i class="bi bi-star-fill" style="color: #facc15;"></i> {{ $quest->exp_reward }} EXP</span>
                <span class="reward-tag" style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: flex; align-items: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.7); padding: 0.25rem 0.75rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);"><i class="bi bi-coin" style="color: #eab308;"></i> {{ $quest->gold_reward }} Gold</span>
                @if($quest->stat_reward_type)
                <span class="reward-tag" style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: flex; align-items: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.7); padding: 0.25rem 0.75rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <i class="bi bi-brain" style="color: #00d4ff;"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                </span>
                @endif
            </div>
        </div>
        
        {{-- Tombol Aksi Admin --}}
        <div class="quest-actions" style="display: flex; flex-direction: column; gap: 0.75rem; min-width: 160px; flex-shrink: 0; margin-left: auto;">
            {{-- TOMBOL EDIT (bisa Anda tambahkan nanti) --}}
            {{-- <a href="#" class="btn btn-warning" style="...">Edit</a> --}}
            
            {{-- TOMBOL HAPUS --}}
            <form action="{{ route('admin.quests.destroy', $quest->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus quest ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(220, 38, 38, 0.15); color: #f87171; border: 1px solid rgba(220, 38, 38, 0.4);">
                    <i class="bi bi-trash-fill"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="empty-state-text" style="color: #94a3b8; text-align: center; padding: 2rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        Anda belum membuat quest admin.
    </p>
    @endforelse

    {{-- Link Paginasi --}}
    <div class="pagination-links" style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $adminQuests->links() }}
    </div>
</div>
@endsection