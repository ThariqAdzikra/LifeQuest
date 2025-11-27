@extends('layouts.app')

@section('title', 'Character Stats - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS --}}
<link rel="stylesheet" href="{{ asset('css/achievement/style.css') }}">
@endpush

@section('content')

{{-- Helper PHP untuk menghitung Level & EXP --}}
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

<div class="quest-board-container">
    
    {{-- Judul Halaman --}}
    <h1 class="page-title">
        <i class="bi bi-person-circle page-title-icon"></i>
        Character Stats
    </h1>
    <p class="page-subtitle">Statistik dan progres Anda di LifeQuest</p>

    {{-- Grid Statistik --}}
    <div class="stats-container">

        {{-- Kartu Level & EXP --}}
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

        {{-- Kartu Atribut: Gold --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon gold">
                <i class="bi bi-coin"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Gold</div>
                <div class="stat-value">{{ number_format($user->gold ?? 0) }}</div>
            </div>
        </div>

        {{-- Kartu Atribut: Quest Completed --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon completed">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Quests Completed</div>
                <div class="stat-value">{{ $user->questLogs->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        {{-- Kartu Atribut: Strength --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon strength">
                <i class="bi bi-person-arms-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Strength</div>
                <div class="stat-value">{{ $user->strength ?? 0 }}</div>
            </div>
        </div>

        {{-- Kartu Atribut: Stamina --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon stamina">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Stamina</div>
                <div class="stat-value">{{ $user->stamina ?? 0 }}</div>
            </div>
        </div>

        {{-- Kartu Atribut: Intelligence --}}
        <div class="glass-card stat-card-attribute">
            <div class="stat-icon intelligence">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Intelligence</div>
                <div class="stat-value">{{ $user->intelligence ?? 0 }}</div>
            </div>
        </div>

        {{-- Kartu Atribut: Agility --}}
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

    {{-- Judul Section Achievements --}}
    <h2 class="section-title-sub">
        <i class="bi bi-trophy-fill"></i> Unlocked Achievements
    </h2>
    
    {{-- Kontainer Achievements --}}
    <div class="unlocked-achievements-container">
        
        @forelse ($unlockedAchievements as $achievement)
            
            <div class="glass-card achievement-card">
                {{-- Gambar Achievement dengan Rarity --}}
                <div class="achievement-image-container rarity-{{ $achievement->rarity }}">
                    <img src="{{ $achievement->icon_path ? asset($achievement->icon_path) : asset('images/default-trophy.png') }}" 
                         alt="{{ $achievement->title }}" 
                         class="achievement-image">
                </div>
                
                {{-- Info Achievement --}}
                <div class="achievement-info">
                    <h3 class="rarity-{{ $achievement->rarity }}">{{ $achievement->title }}</h3>
                    <p>{{ $achievement->description }}</p>
                </div>
            </div>
            
        @empty
            {{-- Empty State --}}
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