@extends('layouts.app')

@section('title', 'Quest Board - LifeQuest')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/quest/style.css') }}">
@endpush

@section('content')
<div class="quest-board-container">
    {{-- Judul Halaman --}}
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem; background: linear-gradient(135deg, #00d4ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-family: 'Orbitron', sans-serif;">
        Quest Board
    </h1>
    <p style="color: #b0b0c0; margin-bottom: 2rem;">Selesaikan tugas, raih prestasi, dan tingkatkan level karakter Anda di dunia nyata.</p>

    {{-- Navigasi Tab --}}
    <div class="quest-tabs">
        <button class="tab-link active" onclick="openTab(event, 'myQuests')"><i class="bi bi-person-check-fill"></i> Quest Saya</button>
        <button class="tab-link" onclick="openTab(event, 'availableQuests')"><i class="bi bi-journal-album"></i> Quest Tersedia</button>
        <button class="tab-link" onclick="openTab(event, 'createQuest')"><i class="bi bi-plus-circle-dotted"></i> Buat Quest</button>
        <button class="tab-link" onclick="openTab(event, 'completedQuests')"><i class="bi bi-archive-fill"></i> Riwayat</button>
    </div>

    {{-- Konten Tab 1: Quest Saya (Yang sedang diambil) --}}
    <div id="myQuests" class="tab-content active">
        <h2 class="section-title" style="text-align: left; font-size: 1.8rem; margin-bottom: 1.5rem;">Quest Aktif</h2>
        
        @forelse ($myQuests as $log)
        <div class="quest-card">
            <div class="quest-info">
                <span class="reward-tag" style="margin-bottom: 0.5rem;"><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($log->quest->frequency) }}</span>
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
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="bi bi-check-lg"></i> Selesaikan</button>
                </form>
                <form action="{{ route('quests.cancel', $log->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;"><i class="bi bi-x-lg"></i> Batalkan</button>
                </form>
            </div>
        </div>
        @empty
        <p style="color: #b0b0c0;">Anda belum mengambil quest apapun. Kunjungi tab "Quest Tersedia" untuk memulai!</p>
        @endforelse
    </div>

    {{-- Konten Tab 2: Quest Tersedia (Admin & User lain) --}}
    <div id="availableQuests" class="tab-content">
        <h2 class="section-title" style="text-align: left; font-size: 1.8rem; margin-bottom: 1.5rem;">Quest Resmi (Admin)</h2>
        
        @forelse ($adminQuests as $quest)
        <div class="quest-card">
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
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="bi bi-plus-lg"></i> Ambil Quest</button>
                </form>
            </div>
        </div>
        @empty
        <p style="color: #b0b0c0;">Tidak ada quest resmi yang tersedia saat ini.</p>
        @endforelse
        
        <h2 class="section-title" style="text-align: left; font-size: 1.8rem; margin-top: 3rem; margin-bottom: 1.5rem;">Quest Komunitas</h2>
        
        @forelse ($userQuests as $quest)
        <div class="quest-card">
            <div class="quest-info">
                <h3>{{ $quest->title }}</h3>
                <p>{{ $quest->description }}</p>
                <small style="color: #a78bfa; margin-top: 0.5rem; display: block;">Dibuat oleh: {{ $quest->creator->name }}</small>
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
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="bi bi-plus-lg"></i> Ambil Quest</button>
                </form>

                {{-- --- PERUBAHAN DI SINI: Tombol Hapus --- --}}
                {{-- Tampilkan tombol Hapus HANYA jika user yang login adalah pembuat quest --}}
                @if(Auth::id() == $quest->creator_id)
                <form action="{{ route('quests.destroy', $quest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus quest ini? Ini tidak dapat diurungkan.');">
                    @csrf
                    @method('DELETE')
                    {{-- Style tombol hapus disesuaikan agar pas --}}
                    <button type="submit" class="btn btn-danger" style="width: 100%; padding: 0.75rem 1rem; font-size: 0.9rem;">
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
                @endif
                {{-- --- AKHIR PERUBAHAN --- --}}
                
            </div>
        </div>
        @empty
        <p style="color: #b0b0c0;">Tidak ada quest dari komunitas yang tersedia saat ini.</p>
        @endforelse
    </div>

    {{-- Konten Tab 3: Buat Quest Sendiri --}}
    <div id="createQuest" class="tab-content">
        <h2 class="section-title" style="text-align: left; font-size: 1.8rem; margin-bottom: 1.5rem;">Buat Quest Kustom</h2>
        
        <form action="{{ route('quests.store') }}" method="POST" style="max-width: 800px;">
            @csrf
            <div class="form-group">
                <label for="title">Judul Quest</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Belajar Laravel 1 Jam" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-control" placeholder="Deskripsikan aktivitas yang harus dilakukan..."></textarea>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
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
             <p style="color: #b0b0c0; font-size: 0.9rem; margin-bottom: 1.5rem;">Hadiah EXP, Gold, dan Poin Stat akan dihitung otomatis berdasarkan Kesulitan.</p>
            
             <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Simpan Quest</button>
        </form>
    </div>
    
    {{-- Konten Tab 4: Riwayat Quest Selesai --}}
    <div id="completedQuests" class="tab-content">
         <h2 class="section-title" style="text-align: left; font-size: 1.8rem; margin-bottom: 1.5rem;">Quest Selesai</h2>
         
         @forelse ($completedQuests as $log)
         <div class="quest-card" style="opacity: 0.7; border-color: rgba(52, 211, 153, 0.5);">
            <div class="quest-info">
                <h3 style="color: #34d399; text-decoration: line-through;">{{ $log->quest->title }}</h3>
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
         <p style="color: #b0b0c0;">Anda belum menyelesaikan quest apapun.</p>
         @endforelse
    </div>

</div>
@endsection

@push('scripts')
{{-- Memanggil file JS kustom --}}
<script src="{{ asset('js/quest/main.js') }}"></script>
@endpush