@extends('layouts.app')

@section('title', 'Leaderboard - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/leaderboard/style.css') }}">
@endpush

@section('content')

<div class="leaderboard-container">
    
    <h1 class="page-title">
        <i class="bi bi-bar-chart-line-fill page-title-icon"></i>
        Leaderboard
    </h1>
    <p class="page-subtitle">Lihat posisimu di antara para petualang LifeQuest lainnya.</p>

    {{-- 
      (DIUBAH) Hanya tampilkan jika 'is_admin' adalah 0 (BUKAN 'role')
    --}}
    @if(Auth::user()->is_admin == 0 && $currentUserRank)
        <div class="glass-card user-rank-card">
            <div class="rank-display">
                <div class="rank-label">Peringkat Anda</div>
                <div class="rank-value">#{{ $currentUserRank }}</div>
            </div>
            <div class="user-info">
                <div class="user-name">{{ $currentUser->name }}</div>
                <div class="user-stats">
                    <span>
                        <i class="bi bi-shield-fill-check"></i> 
                        {{-- Ini sudah benar karena DB Anda punya 'level' --}}
                        Level {{ $currentUser->level }} 
                    </span>
                    <span>
                        <i class="bi bi-star-fill"></i> 
                        {{-- (DIUBAH) Menggunakan 'experience_points' (BUKAN 'exp') --}}
                        {{ number_format($currentUser->experience_points) }} EXP
                    </span>
                    <span>
                        <i class="bi bi-coin"></i> 
                        {{ number_format($currentUser->gold) }} Gold
                    </span>
                </div>
            </div>
        </div>
    @endif
    {{-- Akhir dari kondisi --}}


    <h2 class="section-title-sub">
        <i class="bi bi-trophy-fill"></i> Top 20 Users
    </h2>

    <div class="glass-card leaderboard-list">
        <div class="leaderboard-header">
            <div class="header-rank">Rank</div>
            <div class="header-user">User</div>
            <div class="header-level">Level</div>
            <div class="header-exp">EXP</div>
            <div class="header-gold">Gold</div>
        </div>

        @foreach ($topUsers as $user)
            @php
                $rank = $loop->iteration;
                $rankClass = '';
                if ($rank == 1) $rankClass = 'rank-1';
                if ($rank == 2) $rankClass = 'rank-2';
                if ($rank == 3) $rankClass = 'rank-3';
                if ($user->id == $currentUser->id) $rankClass .= ' current-user-row';
            @endphp
        
            <div class="leaderboard-row {{ $rankClass }}">
                <div class="rank-col">
                    @if($rank == 1) <i class="bi bi-trophy-fill"></i>
                    @elseif($rank == 2) <i class="bi bi-award-fill"></i>
                    @elseif($rank == 3) <i class="bi bi-patch-check-fill"></i>
                    @endif
                    {{ $rank }}
                </div>
                <div class="user-col">
                    {{ $user->name }}
                </div>
                <div class="level-col">
                    {{-- Ini sudah benar --}}
                    {{ $user->level }}
                </div>
                <div class="exp-col">
                    {{-- (DIUBAH) Menggunakan 'experience_points' (BUKAN 'exp') --}}
                    {{ number_format($user->experience_points) }}
                </div>
                <div class="gold-col">
                    {{ number_format($user->gold) }}
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection