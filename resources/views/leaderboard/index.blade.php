@extends('layouts.app')

@section('title', 'Leaderboard - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS kustom untuk leaderboard --}}
<link rel="stylesheet" href="{{ asset('css/leaderboard/style.css') }}">
@endpush

@section('content')

{{-- Menggunakan container yang sama dengan halaman lain --}}
<div class="leaderboard-container">
    
    {{-- Judul Halaman (gaya dari patokan) --}}
    <h1 class="page-title">
        <i class="bi bi-bar-chart-line-fill page-title-icon"></i>
        Leaderboard
    </h1>
    <p class="page-subtitle">Lihat posisimu di antara para petualang LifeQuest lainnya.</p>

    {{-- 
      ==================================================================
      KARTU PERINGKAT PENGGUNA (HANYA TAMPIL JIKA BUKAN ADMIN)
      ==================================================================
    --}}
    {{-- [PERBAIKAN] Menggunakan 'is_admin == 0' agar konsisten dengan controller --}}
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
                        {{-- Menggunakan $currentUser->level (dari Accessor) --}}
                        Level {{ $currentUser->level }}
                    </span>
                    {{-- [PERUBAHAN] Menampilkan stat baru (Quest Admin Selesai) --}}
                    <span>
                        <i class="bi bi-patch-check-fill"></i> 
                        {{ $currentUserQuestCount }} Quest Admin
                    </span>
                    {{-- [PERBAIKAN] Menggunakan 'exp' (sesuai User.php) --}}
                    <span>
                        <i class="bi bi-star-fill"></i> 
                        {{ number_format($currentUser->exp) }} EXP
                    </span>
                </div>
            </div>
        </div>
    @endif
    {{-- Akhir dari kondisi --}}


    {{-- Judul Section (gaya dari patokan) --}}
    <h2 class="section-title-sub">
        <i class="bi bi-trophy-fill"></i> Top 20 Users
    </h2>

    {{-- Daftar Leaderboard --}}
    <div class="glass-card leaderboard-list">
        {{-- Header Tabel --}}
        <div class="leaderboard-header">
            <div class="header-rank">Rank</div>
            <div class="header-user">User</div>
            <div class="header-level">Level</div>
            {{-- [PERUBAHAN] Label kolom diganti --}}
            <div class="header-exp">Quest Admin</div>
            <div class="header-gold">Total EXP</div>
        </div>

        {{-- Body Tabel (Looping) --}}
        @foreach ($topUsers as $user)
            @php
                $rank = $loop->iteration;
                $rankClass = '';
                if ($rank == 1) $rankClass = 'rank-1';
                if ($rank == 2) $rankClass = 'rank-2';
                if ($rank == 3) $rankClass = 'rank-3';
                // Highlight juga baris jika itu adalah user yang sedang login
                if ($user->id == $currentUser->id) $rankClass .= ' current-user-row';
            @endphp
        
            <div class="leaderboard-row {{ $rankClass }}">
                <div class="rank-col">
                    {{-- Ikon untuk Top 3 --}}
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
                    {{-- Menggunakan $user->level (dari Accessor) --}}
                    {{ $user->level }}
                </div>
                {{-- [PERBAIKAN] Menampilkan 'quest_logs_count' (dari Controller) --}}
                <div class="exp-col">
                    {{ $user->quest_logs_count }}
                </div>
                {{-- [PERBAIKAN] Menampilkan 'exp' (sesuai User.php) --}}
                <div class="gold-col">
                    {{ number_format($user->exp) }}
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection