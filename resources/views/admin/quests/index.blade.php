@extends('layouts.app')

@section('title', 'Kelola Quest Admin - LifeQuest')

@push('styles')
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/admin/quest.css') }}">
@endpush

@section('content')
<div class="quest-board-container">

    {{-- Header Halaman --}}
    <div class="page-header-admin">
        <div>
            <h1 class="page-title">
                <i class="bi bi-shield-check"></i>
                Kelola Quest Admin
            </h1>
            <p class="page-subtitle">Buat, edit, atau hapus quest resmi untuk player.</p>
        </div>
        
        {{-- Tombol 'Buat Baru' --}}
        <a href="{{ route('admin.quests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
    </div>

    @include('partials.admin_alerts') {{-- Tampilkan notifikasi (jika ada) --}}

    {{-- Wrapper utama untuk daftar quest --}}
    <div class="glass-card manage-quest-wrapper">
        @forelse ($adminQuests as $quest)
        <div class="quest-card-inner"> 
            <div class="quest-info">
                <h3>{{ $quest->title }}</h3>
                
                <div class="quest-meta">
                    <span><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($quest->frequency) }}</span>
                    <span><i class="bi bi-bar-chart-line"></i> Kesulitan: {{ ucfirst($quest->difficulty) }}</span>
                </div>
                
                @if($quest->description)
                    <p>{{ $quest->description }}</p>
                @endif
                
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                    @if($quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                    </span>
                    @endif
                    
                    @if($quest->achievement)
                    <span class="reward-tag reward-tag-achievement">
                         @if($quest->achievement->icon_path)
                         <img src="{{ Storage::url($quest->achievement->icon_path) }}" alt="icon" class="achievement-icon">
                         @else
                         <i class="bi bi-award-fill"></i> 
                         @endif
                        Title: {{ $quest->achievement->title }}
                    </span>
                    @endif
                </div>
            </div>
            
            {{-- Tombol Aksi Admin --}}
            <div class="quest-actions">
                
                {{-- [PERUBAHAN] TOMBOL EDIT TELAH DIHAPUS --}}
                
                {{-- TOMBOL HAPUS (Gunakan SweetAlert) --}}
                <form action="{{ route('admin.quests.destroy', $quest->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    {{-- Class 'btn-delete-quest' akan ditangkap oleh JS --}}
                    <button type="submit" class="btn btn-danger btn-delete-quest">
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        {{-- Tampilan jika tidak ada quest --}}
        <p class="empty-state-text"> 
            Anda belum membuat quest admin.
        </p>
        @endforelse

        {{-- Tampilkan Link Paginasi --}}
        @if ($adminQuests->hasPages())
            <div class="quest-pagination-container" data-section="admin-quests">
                {{ $adminQuests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
{{-- 1. Panggil SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- 2. Panggil file JS kustom Anda --}}
<script src="{{ asset('js/admin/quest.js') }}"></script>
@endpush


{{-- Helper function (Tetap di sini atau pindah ke AppServiceProvider/Helpers) --}}
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