@extends('layouts.app')

@section('title', 'Buat Quest Admin - LifeQuest')

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
            <p class="page-subtitle">Buat quest resmi baru untuk semua player.</p>
        </div>
        <a href="{{ route('admin.quests.index') }}" class="btn btn-secondary-glass">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Judul Section Form --}}
    <div class="section-header">
        <i class="bi bi-pencil-square"></i>
        <h2 class="section-title">
            Formulir Quest Baru
        </h2>
    </div>
    
    {{-- Card Form --}}
    <div class="glass-card">
        
        {{-- Tampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="validation-errors">
                <strong>Oops! Ada yang salah:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.quests.store') }}" method="POST">
            @csrf
            
            {{-- Judul --}}
            <div class="form-group">
                <label for="title">Judul Quest</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Kalahkan Raja Goblin" value="{{ old('title') }}" required>
            </div>
            
            {{-- Deskripsi --}}
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-control" placeholder="Deskripsikan quest...">{{ old('description') }}</textarea>
            </div>
            
            <div class="form-grid">
                {{-- Kesulitan --}}
                <div class="form-group">
                    <label for="difficulty">Kesulitan</label>
                    {{-- Menggunakan class .form-control agar panah kustom tampil --}}
                    <select id="difficulty" name="difficulty" class="form-control">
                        <option value="easy" @if(old('difficulty') == 'easy') selected @endif>Mudah</option>
                        <option value="medium" @if(old('difficulty') == 'medium') selected @endif>Sedang</option>
                        <option value="hard" @if(old('difficulty') == 'hard') selected @endif>Sulit</option>
                    </select>
                </div>
                {{-- Frekuensi --}}
                <div class="form-group">
                    <label for="frequency">Frekuensi</label>
                    <select id="frequency" name="frequency" class="form-control">
                        <option value="once" @if(old('frequency') == 'once') selected @endif>Sekali Jalan</option>
                        <option value="daily" @if(old('frequency') == 'daily') selected @endif>Harian</option>
                        <option value="weekly" @if(old('frequency') == 'weekly') selected @endif>Mingguan</option>
                    </select>
                </div>
            </div>
            
            <hr class="form-divider">
            
            {{-- Reward (Manual Input) --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="exp_reward">Reward EXP</label>
                    <input type="number" id="exp_reward" name="exp_reward" class="form-control" value="{{ old('exp_reward', 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label for="gold_reward">Reward Gold</label>
                    <input type="number" id="gold_reward" name="gold_reward" class="form-control" value="{{ old('gold_reward', 0) }}" min="0" required>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="stat_reward_type">Reward Stat</label>
                    <select id="stat_reward_type" name="stat_reward_type" class="form-control">
                        <option value="">Tidak Ada</option>
                        <option value="intelligence" @if(old('stat_reward_type') == 'intelligence') selected @endif>Intelligence</option>
                        <option value="strength" @if(old('stat_reward_type') == 'strength') selected @endif>Strength</option>
                        <option value="stamina" @if(old('stat_reward_type') == 'stamina') selected @endif>Stamina</option>
                        <option value="agility" @if(old('stat_reward_type') == 'agility') selected @endif>Agility</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stat_reward_value">Jumlah Stat</label>
                    <input type="number" id="stat_reward_value" name="stat_reward_value" class="form-control" value="{{ old('stat_reward_value', 0) }}" min="0">
                </div>
            </div>

            <hr class="form-divider">

            {{-- Dropdown Achievement --}}
            <div class="form-group">
                <label for="achievement_id">Hadiah Achievement (Title)</label>
                <select id="achievement_id" name="achievement_id" class="form-control">
                    <option value="">Tidak ada</option>
                    @foreach ($achievements as $achievement)
                        <option value="{{ $achievement->id }}" @if(old('achievement_id') == $achievement->id) selected @endif>
                            {{ $achievement->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="bi bi-save-fill"></i> Simpan Quest Admin
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
{{-- (File JS ini bisa diisi jika ada interaksi form kustom nanti) --}}
<script src="{{ asset('js/admin/quest.js') }}"></script>
@endpush