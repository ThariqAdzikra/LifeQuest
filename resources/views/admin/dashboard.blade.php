@extends('layouts.app') 

@section('content')
<div classall="quest-board-container" style="max-width: 1140px; margin: 0 auto; padding: 2rem;">

    <h1 class="page-title" style="font-size: 2.5rem; margin-bottom: 0.5rem; background: linear-gradient(135deg, #00d4ff, #00aeff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-family: 'Orbitron', sans-serif;">
        Admin Dashboard
    </h1>
    <p class="page-subtitle" style="color: #94a3b8; margin-bottom: 2rem; font-size: 1.1rem;">
        Selamat datang, {{ Auth::user()->name }}.
    </p>

    {{-- --- MODIFIKASI DISINI --- --}}
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        {{-- Tombol 'Buat Quest' --}}
        <a href="{{ route('admin.quests.create') }}" class="btn btn-primary" style="flex-grow: 1; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #00d4ff; color: #0a0e27; box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);">
            <i class="bi bi-plus-circle-fill"></i> Buat Quest Admin Baru
        </a>
        
        {{-- TOMBOL BARU 'Kelola Quest' --}}
        <a href="{{ route('admin.quests.index') }}" class="btn" style="flex-grow: 1; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); color: #e2e8f0;">
            <i class="bi bi-list-task"></i> Kelola Quest Admin
        </a>
    </div>
    {{-- --- AKHIR MODIFIKASI --- --}}


    {{-- 1. KARTU JUMLAH USER --}}
    <div class="section-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 0.75rem;">
        <i class="bi bi-people-fill" style="font-size: 1.2rem; color: #00d4ff;"></i>
        <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
            Pantau Pengguna
        </h2>
    </div>
    
    <div class="glass-card" style="padding: 1.5rem; margin-bottom: 2.5rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        <h3 style="font-family: 'Orbitron', sans-serif; margin-bottom: 0.5rem; color: #e2e8f0; letter-spacing: 1px;">Total Pengguna Aktif</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: #00d4ff; margin: 0;">
            {{ $userCount }}
        </p>
        <small style="color: #94a3b8;">(Tidak termasuk admin)</small>
    </div>


    {{-- 2. TABEL LEADERBOARD --}}
    <div class="section-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 0.75rem;">
        <i class="bi bi-trophy-fill" style="font-size: 1.2rem; color: #00d4ff;"></i>
        <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
            Leaderboard Pengguna (Top 10 EXP)
        </h2>
    </div>
    
    <div class="glass-card" style="padding: 1rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
            <thead style="border-bottom: 2px solid rgba(0, 212, 255, 0.5);">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-size: 1.1rem; color: #e2e8f0;">Peringkat</th>
                    <th style="padding: 1rem; text-align: left; font-size: 1.1rem; color: #e2e8f0;">Nama User</th>
                    <th style="padding: 1rem; text-align: left; font-size: 1.1rem; color: #e2e8f0;">Level</th>
                    <th style="padding: 1rem; text-align: left; font-size: 1.1rem; color: #e2e8f0;">Total EXP</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaderboard as $index => $user)
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                        <td style="padding: 1rem; font-size: 1.2rem; font-weight: 700; color: #00d4ff;">
                            #{{ $index + 1 }}
                        </td>
                        <td style="padding: 1rem; color: #cbd5e1;">{{ $user->name }}</td>
                        <td style="padding: 1rem; color: #cbd5e1;">Level {{ $user->level }}</td>
                        <td style="padding: 1rem; color: #cbd5e1;">{{ $user->exp }} EXP</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #94a3b8;">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection