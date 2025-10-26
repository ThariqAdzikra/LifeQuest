@extends('layouts.app')

@section('title', 'Character Stats - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/quest/style.css') }}">
{{-- Link ke file CSS baru untuk achievement --}}
<link rel="stylesheet" href="{{ asset('css/achievement/style.css') }}">
@endpush

@section('content')

{{-- Helper PHP untuk menghitung Level dan EXP --}}
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
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; background: linear-gradient(135deg, #00d4ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-family: 'Orbitron', sans-serif; font-weight: 700; display: flex; align-items: center; gap: 1rem;">
        <i class="bi bi-person-circle" style="color: #00d4ff; -webkit-text-fill-color: #00d4ff;"></i>
        Character Stats
    </h1>
    <p style="color: #94a3b8; margin-bottom: 2.5rem; font-size: 1.1rem;">Statistik dan progres Anda di LifeQuest</p>

    {{-- Grid Statistik --}}
    <div class="stats-container">

        {{-- Kartu Level & EXP --}}
        <div class="stat-card-primary" style="grid-column: 1 / -1;">
            <div class="stat-value" style="color: #facc15;">
                <i class="bi bi-person-badge-fill"></i> {{ $user->name }}
            </div>
            <div class="stat-label" style="font-size: 1.5rem; color: #e0e0e0;">
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

        {{-- Kartu Gold --}}
        <div class="stat-card-primary">
            <div class="stat-value" style="color: #eab308;">
                <i class="bi bi-coin"></i> {{ number_format($user->gold ?? 0) }}
            </div>
            <div class="stat-label">
                <i class="bi bi-currency-exchange"></i> Gold
            </div>
        </div>

        {{-- Kartu Quest Selesai --}}
        <div class="stat-card-primary">
            <div class="stat-value" style="color: #34d399;">
                <i class="bi bi-check-circle-fill"></i> {{ $user->questLogs->where('status', 'completed')->count() }}
            </div>
            <div class="stat-label">
                <i class="bi bi-list-check"></i> Quests Completed
            </div>
        </div>

        {{-- Grid Atribut --}}
        <div style="grid-column: 1 / -1; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            
            {{-- Strength --}}
            <div class="stat-card-attribute">
                <div class="stat-icon strength">
                    <i class="bi bi-person-arms-up"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Strength</div>
                    <div class="stat-value">{{ $user->strength ?? 0 }}</div>
                </div>
            </div>

            {{-- Stamina --}}
            <div class="stat-card-attribute">
                <div class="stat-icon stamina">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Stamina</div>
                    <div class="stat-value">{{ $user->stamina ?? 0 }}</div>
                </div>
            </div>

            {{-- Intelligence --}}
            <div class="stat-card-attribute">
                <div class="stat-icon intelligence">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Intelligence</div>
                    <div class="stat-value">{{ $user->intelligence ?? 0 }}</div>
                </div>
            </div>

            {{-- Agility --}}
            <div class="stat-card-attribute">
                <div class="stat-icon agility">
                    <i class="bi bi-wind"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Agility</div>
                    <div class="stat-value">{{ $user->agility ?? 0 }}</div>
                </div>
            </div>

        </div>

    </div>

    {{-- Bagian Unlocked Achievements --}}
    <h2 class="achievement-section-title">
        <i class="bi bi-trophy-fill"></i> Unlocked Achievements
    </h2>

    <div class="unlocked-achievements-container">
        @forelse ($unlockedAchievements as $achievement)
            <div class="achievement-card">
                
                {{-- Helper untuk ikon berdasarkan rarity --}}
                @php
                    $icon = 'bi-patch-check-fill'; // default: common
                    $iconColor = '#facc15';
                    
                    if ($achievement->rarity == 'rare') {
                        $icon = 'bi-shield-fill-check';
                        $iconColor = '#3b82f6';
                    }
                    if ($achievement->rarity == 'epic') {
                        $icon = 'bi-gem';
                        $iconColor = '#a855f7';
                    }
                    if ($achievement->rarity == 'legendary') {
                        $icon = 'bi-award-fill';
                        $iconColor = '#f59e0b';
                    }
                @endphp

                <div class="achievement-icon" style="border-color: {{ $iconColor }}; color: {{ $iconColor }};">
                    <i class="bi {{ $icon }}"></i>
                </div>
                
                <div class="achievement-info">
                    <h3>{{ $achievement->title }}</h3>
                    <p>{{ $achievement->description }}</p>
                    
                    <div class="quest-rewards">
                        <span class="reward-tag">
                            <i class="bi bi-star-fill"></i> {{ $achievement->exp_reward }} EXP
                        </span>
                        <span class="reward-tag">
                            <i class="bi bi-coin"></i> {{ $achievement->gold_reward }} Gold
                        </span>
                        @if($achievement->rarity)
                        <span class="reward-tag" style="border-color: {{ $iconColor }}; color: {{ $iconColor }};">
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
            <p style="color: #94a3b8; text-align: center; padding: 3rem 2rem; background: rgba(15, 23, 42, 0.6); border-radius: 12px; border: 1px dashed rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); font-size: 1.1rem;">
                <i class="bi bi-trophy" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                Anda belum mendapatkan achievement apapun.<br>
                <span style="font-size: 0.95rem; color: #64748b;">Selesaikan quest untuk membuka achievement pertama Anda!</span>
            </p>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
{{-- Script untuk animasi tambahan jika diperlukan --}}
<script>
    // Animasi smooth untuk progress bar saat halaman load
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