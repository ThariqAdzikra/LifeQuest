@extends('layouts.app')

@section('title', 'Character Stats - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Pastikan Anda memanggil file CSS yang benar dari langkah 2 --}}
<link rel="stylesheet" href="{{ asset('css/achievement/style.css') }}">
@endpush

@section('content')

{{-- Helper PHP (Tidak berubah) --}}
@php
    function calculateLevel($exp) {
        if ($exp <= 0) return 1;
        return floor(pow($exp / 100, 0.5)) + 1;
    }

    function getExpForLevel($level) {
        if ($level <= 1) return 0;
        return 100 * pow($level - 1, 2);
    }

    $currentLevel = calculateLevel($user->exp);
    $expForCurrentLevel = getExpForLevel($currentLevel);
    $expForNextLevel = getExpForLevel($currentLevel + 1);

    $expInCurrentLevel = $user->exp - $expForCurrentLevel;
    $expNeededForNextLevel = $expForNextLevel - $expForCurrentLevel;
    $expPercentage = ($expNeededForNextLevel > 0) ? ($expInCurrentLevel / $expNeededForNextLevel) * 100 : 0;
@endphp

{{-- Menggunakan container yang sama --}}
<div class="quest-board-container">
    
    {{-- Judul Halaman (Sudah menggunakan class) --}}
    <h1 class="page-title">
        <i class="bi bi-person-circle page-title-icon"></i>
        Character Stats
    </h1>
    <p class="page-subtitle">Statistik dan progres Anda di LifeQuest</p>

    {{-- Grid Statistik --}}
    <div class="stats-container">

        {{-- Kartu Level & EXP (Full-width) --}}
        <div class="glass-card stat-card-primary" style="grid-column: 1 / -1;">
            <div class="stat-value">
                <i class="bi bi-person-badge-fill"></i> {{ $user->name }}
            </div>
            <div class="stat-label">
                <i class="bi bi-shield-fill-check"></i> Level {{ $currentLevel }}
            </div>
            
            {{-- EXP Bar --}}
            <div class="exp-bar-container">
                <div class="exp-bar-fill" style="width: {{ $expPercentage }}%;"></div>
            </div>
            <div class="exp-text">
                <i class="bi bi-star-fill"></i> EXP: {{ $expInCurrentLevel }} / {{ $expNeededForNextLevel }} (Total: {{ $user->exp }})
            </div>
        </div>

        {{-- 
          =============================================
          PERBAIKAN UTAMA DI SINI
          Semua 6 kartu di bawah ini sekarang konsisten
          =============================================
        --}}
        
        {{-- (DIUBAH) Kartu Gold (dibuat sama seperti Strength) --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon gold">
                <i class="bi bi-coin"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Gold</div>
                <div class="stat-value">{{ number_format($user->gold ?? 0) }}</div>
            </div>
        </div>

        {{-- (DIUBAH) Kartu Quest (dibuat sama seperti Strength) --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon completed">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Quests Completed</div>
                <div class="stat-value">{{ $user->questLogs->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        {{-- Kartu Atribut (Struktur ini sudah benar) --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon strength">
                <i class="bi bi-person-arms-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Strength</div>
                <div class="stat-value">{{ $user->strength ?? 0 }}</div>
            </div>
        </div>

        <div class="glass-card stat-card-attribute">
            <div class="stat-icon stamina">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Stamina</div>
                <div class="stat-value">{{ $user->stamina ?? 0 }}</div>
            </div>
        </div>

        <div class="glass-card stat-card-attribute">
            <div class="stat-icon intelligence">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Intelligence</div>
                <div class="stat-value">{{ $user->intelligence ?? 0 }}</div>
            </div>
        </div>

        <div class="glass-card stat-card-attribute">
            <div class="stat-icon agility">
                <i class="bi bi-wind"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Agility</div>
                <div class="stat-value">{{ $user->agility ?? 0 }}</div>
            </div>
        </div>

    </div>

    {{-- Judul Section (Sudah menggunakan class) --}}
    <h2 class="section-title-sub">
        <i class="bi bi-trophy-fill"></i> Unlocked Achievements
    </h2>

    <div class="unlocked-achievements-container">
        @forelse ($unlockedAchievements as $achievement)
            {{-- Kartu Achievement (Sudah menggunakan class) --}}
            <div class="glass-card achievement-card">
                
                @php
                    $icon = 'bi-patch-check-fill'; // default
                    if ($achievement->rarity == 'rare') $icon = 'bi-shield-fill-check';
                    if ($achievement->rarity == 'epic') $icon = 'bi-gem';
                    if ($achievement->rarity == 'legendary') $icon = 'bi-award-fill';
                @endphp

                <div class="achievement-icon rarity-{{ $achievement->rarity }}">
                    <i class="bi {{ $icon }}"></i>
                </div>
                
                <div class="achievement-info">
                    <h3 class="rarity-{{ $achievement->rarity }}">{{ $achievement->title }}</h3>
                    <p>{{ $achievement->description }}</p>
                    
                    <div class="quest-rewards">
                        <span class="reward-tag">
                            <i class="bi bi-star-fill"></i> {{ $achievement->exp_reward }} EXP
                        </span>
                        <span class="reward-tag">
                            <i class="bi bi-coin"></i> {{ $achievement->gold_reward }} Gold
                        </span>
                        @if($achievement->rarity)
                        <span class="reward-tag rarity-{{ $achievement->rarity }}">
                            <i class="bi bi-gem"></i> {{ ucfirst($achievement->rarity) }}
                        </span>
                        @endif
                    </div>
                    
                    @if($achievement->pivot->unlocked_at)
                    <span class="unlocked-date">
                        <i class="bi bi-calendar-check-fill"></i>
                        Unlocked: {{ \Carbon\Carbon::parse($achievement->pivot->unlocked_at)->format('d F Y') }}
                    </span>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty state (Sudah menggunakan class) --}}
            <div class="empty-state-card">
                <i class="bi bi-trophy"></i>
                Anda belum mendapatkan achievement apapun.
                <span>Selesaikan quest untuk membuka achievement pertama Anda!</span>
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
{{-- Script (Tidak berubah) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const expBar = document.querySelector('.exp-bar-fill');
        if (expBar) {
            const width = expBar.style.width;
            expBar.style.width = '0%';
            setTimeout(() => {
                expBar.style.width = width;
            }, 300);
        }
    });
</script>
@endpush