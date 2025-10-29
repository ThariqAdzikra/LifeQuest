@extends('layouts.app')

{{-- Tambahkan title --}}
@section('title', 'Kelola Quest Admin - LifeQuest')

{{-- Push style jika diperlukan (misal, style.css dari quest player) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/quest/style.css') }}"> {{-- Sesuaikan path jika beda --}}
@endpush

@section('content')
<div class="quest-board-container"> {{-- Gunakan container utama --}}

    {{-- Header Halaman --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title"> {{-- Style judul utama --}}
                <i class="bi bi-shield-check page-title-icon"></i> {{-- Tambahkan ikon jika mau --}}
                Kelola Quest Admin
            </h1>
            <p class="page-subtitle">Buat, edit, atau hapus quest resmi untuk player.</p> {{-- Style subjudul --}}
        </div>
        
        {{-- Tombol 'Buat Baru' --}}
        <a href="{{ route('admin.quests.create') }}" class="btn btn-primary"> {{-- Class Bootstrap/kustom --}}
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
    </div>

    @include('partials.admin_alerts') {{-- Tampilkan notifikasi --}}

    {{-- Wrapper utama untuk daftar quest --}}
    <div class="glass-card manage-quest-wrapper">
        @forelse ($adminQuests as $quest)
        {{-- Gunakan quest-card-inner untuk setiap item --}}
        <div class="quest-card-inner"> 
            <div class="quest-info">
                <h3>{{ $quest->title }}</h3>
                
                <div class="quest-meta">
                    <span><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($quest->frequency) }}</span>
                    <span><i class="bi bi-bar-chart-line"></i> Kesulitan: {{ ucfirst($quest->difficulty) }}</span>
                </div>
                
                {{-- Tampilkan deskripsi jika ada --}}
                @if($quest->description)
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.5rem;">{{ $quest->description }}</p>
                @endif
                
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                    @if($quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                    </span>
                    @endif
                    {{-- Tampilkan Achievement jika ada --}}
                    @if($quest->achievement) {{-- Cek relasi sudah diload --}}
                    <span class="reward-tag" style="background: rgba(167, 139, 250, 0.15); border-color: rgba(167, 139, 250, 0.4);">
                         @if($quest->achievement->icon_path)
                         <img src="{{ Storage::url($quest->achievement->icon_path) }}" alt="icon" style="width: 16px; height: 16px; border-radius: 4px; margin-right: 4px; object-fit: contain;">
                         @else
                         <i class="bi bi-award-fill" style="color: #a78bfa;"></i> 
                         @endif
                        Title: {{ $quest->achievement->title }}
                    </span>
                    @endif
                </div>
            </div>
            
            {{-- Tombol Aksi Admin --}}
            <div class="quest-actions">
                {{-- TOMBOL EDIT (arahkah ke route edit) --}}
                {{-- 
                <a href="{{ route('admin.quests.edit', $quest->id) }}" class="btn btn-warning"> 
                    <i class="bi bi-pencil-fill"></i> Edit
                </a> 
                --}}
                
                {{-- TOMBOL HAPUS --}}
                <form action="{{ route('admin.quests.destroy', $quest->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus quest ini? Ini juga akan menghapus log terkait.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;"> {{-- Pastikan lebar penuh --}}
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        {{-- Tampilan jika tidak ada quest --}}
        <p class="empty-state-text" style="margin: 0; flex: 1;"> 
            Anda belum membuat quest admin.
        </p>
        @endforelse

        {{-- Tampilkan Link Paginasi di dalam wrapper jika ada quest --}}
        @if ($adminQuests->hasPages())
            <div class="quest-pagination-container" data-section="admin-quests">
                {{ $adminQuests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

{{-- Helper function untuk ikon stat (jika belum ada, taruh di app/Helpers/helpers.php) --}}
@php
if (!function_exists('getStatIcon')) {
    function getStatIcon($statType) {
        $icons = [
            'intelligence' => 'bi bi-brain',
            'strength' => 'bi bi-person-arms-up',
            'stamina' => 'bi bi-lightning-charge-fill',
            'agility' => 'bi bi-wind',
        ];
        return $icons[$statType] ?? 'bi bi-question-circle';
    }
}
@endphp