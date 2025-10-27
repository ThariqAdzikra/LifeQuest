@extends('layouts.app')

@section('title', 'Quest Board - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/quest/style.css') }}">
@endpush

@section('content')
<div class="quest-board-container">
    {{-- Judul Halaman menggunakan class --}}
    <h1 class="page-title">
        Quest Board
    </h1>
    <p class="page-subtitle">Selesaikan tugas, raih prestasi, dan tingkatkan level karakter Anda di dunia nyata.</p>

    {{-- Navigasi Tab --}}
    <div class="quest-tabs">
        <button class="tab-link active" onclick="openTab(event, 'myQuests')"><i class="bi bi-person-check-fill"></i> Quest Saya</button>
        <button class="tab-link" onclick="openTab(event, 'availableQuests')"><i class="bi bi-journal-album"></i> Quest Tersedia</button>
        <button class="tab-link" onclick="openTab(event, 'createQuest')"><i class="bi bi-plus-circle-dotted"></i> Buat Quest</button>
        <button class="tab-link" onclick="openTab(event, 'completedQuests')"><i class="bi bi-archive-fill"></i> Riwayat</button>
    </div>

    {{-- Konten Tab 1: Quest Saya (Yang sedang diambil) --}}
    <div id="myQuests" class="tab-content active">
        
        {{-- Menggunakan section-header style dari dashboard --}}
        <div class="section-header">
            <i class="bi bi-person-check-fill"></i>
            <h2 class="section-title">Quest Aktif</h2>
        </div>
        
        @forelse ($myQuests as $log)
        {{-- Menambahkan class .glass-card --}}
        <div class="quest-card glass-card">
            <div class="quest-info">
                <span class="reward-tag"><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($log->quest->frequency) }}</span>
                <h3>{{ $log->quest->title }}</h3>
                <p>{{ $log->quest->description }}</p>
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $log->quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $log->quest->gold_reward }} Gold</span>
                    @if($log->quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($log->quest->stat_reward_type) }}"></i> +{{ $log->quest->stat_reward_value }} {{ ucfirst($log->quest->stat_reward_type) }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="quest-actions">
                <form action="{{ route('quests.complete', $log->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Selesaikan</button>
                </form>
                <form action="{{ route('quests.cancel', $log->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</button>
                </form>
            </div>
        </div>
        @empty
        {{-- Menggunakan class .empty-state-text --}}
        <p class="empty-state-text">Anda belum mengambil quest apapun. Kunjungi tab "Quest Tersedia" untuk memulai!</p>
        @endforelse
    </div>

    {{-- Konten Tab 2: Quest Tersedia (Admin & User lain) --}}
    <div id="availableQuests" class="tab-content">
        
        {{-- Menggunakan section-header style dari dashboard --}}
        <div class="section-header">
            <i class="bi bi-patch-check-fill"></i>
            <h2 class="section-title">Quest Resmi (Admin)</h2>
        </div>
        
        @forelse ($adminQuests as $quest)
        {{-- Menambahkan class .glass-card --}}
        <div class="quest-card glass-card">
            <div class="quest-info">
                <h3>{{ $quest->title }} ({{ ucfirst($quest->difficulty) }})</h3>
                <p>{{ $quest->description }}</p>
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                    @if($quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="quest-actions">
                <form action="{{ route('quests.take', $quest->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ambil Quest</button>
                </form>
            </div>
        </div>
        @empty
        <p class="empty-state-text">Tidak ada quest resmi yang tersedia saat ini.</p>
        @endforelse
        
        {{-- Menggunakan section-header style dari dashboard --}}
        <div class="section-header" style="margin-top: 3rem;">
            <i class="bi bi-people-fill"></i>
            <h2 class="section-title">Quest Komunitas</h2>
        </div>
        
        @forelse ($userQuests as $quest)
        {{-- Menambahkan class .glass-card --}}
        <div class="quest-card glass-card">
            <div class="quest-info">
                <h3>{{ $quest->title }}</h3>
                <p>{{ $quest->description }}</p>
                <small>Dibuat oleh: {{ $quest->creator->name }}</small>
                <div class="quest-rewards">
                     <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                     <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                     @if($quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="quest-actions">
                <form action="{{ route('quests.take', $quest->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ambil Quest</button>
                </form>

                @if(Auth::id() == $quest->creator_id)
                <form action="{{ route('quests.destroy', $quest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus quest ini? Ini tidak dapat diurungkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <p class="empty-state-text">Tidak ada quest dari komunitas yang tersedia saat ini.</p>
        @endforelse
    </div>

    {{-- Konten Tab 3: Buat Quest Sendiri --}}
    <div id="createQuest" class="tab-content">
        
        {{-- Menggunakan section-header style dari dashboard --}}
        <div class="section-header">
            <i class="bi bi-pencil-square"></i>
            <h2 class="section-title">Buat Quest Kustom</h2>
        </div>
        
        {{-- Membungkus form dalam .glass-card --}}
        <div class="glass-card" style="padding: 2rem;">
            <form action="{{ route('quests.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Judul Quest</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Belajar Laravel 1 Jam" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="form-control" placeholder="Deskripsikan aktivitas yang harus dilakukan..."></textarea>
                </div>
                
                {{-- Menggunakan class untuk grid --}}
                <div class="form-grid">
                    <div class="form-group">
                        <label for="difficulty">Kesulitan</label>
                        <select id="difficulty" name="difficulty" class="form-control">
                            <option value="easy">Mudah</option>
                            <option value="medium">Sedang</option>
                            <option value="hard">Sulit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="frequency">Frekuensi</label>
                        <select id="frequency" name="frequency" class="form-control">
                            <option value="once">Sekali Jalan</option>
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stat_reward_type">Hadiah Stat</label>
                        <select id="stat_reward_type" name="stat_reward_type" class="form-control">
                            <option value="">Tidak Ada</option>
                            <option value="intelligence">Intelligence (Belajar, Membaca)</option>
                            <option value="strength">Strength (Olahraga Angkat Beban)</option>
                            <option value="stamina">Stamina (Lari, Kardio)</option>
                            <option value="agility">Agility (Olahraga, Refleks)</option>
                        </select>
                    </div>
                </div>
                {{-- Menggunakan class untuk hint text --}}
                 <p class="form-hint">Hadiah EXP, Gold, dan Poin Stat akan dihitung otomatis berdasarkan Kesulitan.</p>
                
                 <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Simpan Quest</button>
            </form>
        </div>
    </div>
    
    {{-- Konten Tab 4: Riwayat Quest Selesai --}}
    <div id="completedQuests" class="tab-content">
         
         {{-- Menggunakan section-header style dari dashboard --}}
         <div class="section-header">
            <i class="bi bi-archive-fill"></i>
            <h2 class="section-title">Quest Selesai</h2>
         </div>
         
         @forelse ($completedQuests as $log)
         {{-- Menambahkan class .glass-card dan class .history --}}
         <div class="quest-card glass-card history">
            <div class="quest-info">
                <h3>{{ $log->quest->title }}</h3>
                <p>Diselesaikan pada: {{ $log->updated_at->format('d F Y, H:i') }}</p>
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $log->quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $log->quest->gold_reward }} Gold</span>
                    @if($log->quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($log->quest->stat_reward_type) }}"></i> +{{ $log->quest->stat_reward_value }} {{ ucfirst($log->quest->stat_reward_type) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
         @empty
         <p class="empty-state-text">Anda belum menyelesaikan quest apapun.</p>
         @endforelse
    </div>

</div>
@endsection

@push('scripts')
{{-- Memanggil file JS kustom --}}
<script src="{{ asset('js/quest/main.js') }}"></script>
@endpush