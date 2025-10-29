@extends('layouts.app')

@section('title', 'Quest Board - LifeQuest')

@push('styles')
{{-- [PENAMBAHAN] Import Google Fonts sesuai Style Guide --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/quest/style.css') }}">
@endpush

@section('content')
<div class="quest-board-container">
    {{-- [PERUBAHAN] Judul Halaman dengan Ikon --}}
    <h1 class="page-title">
        <i class="bi bi-journal-check"></i> Quest Board
    </h1>
    <p class="page-subtitle">Selesaikan tugas, raih prestasi, dan tingkatkan level karakter Anda di dunia nyata.</p>

    {{-- Navigasi Tab --}}
    <div class="quest-tabs">
        <button class="tab-link active" onclick="openTab(event, 'myQuests')"><i class="bi bi-person-check-fill"></i> Quest Saya</button>
        <button class="tab-link" onclick="openTab(event, 'availableQuests')"><i class="bi bi-journal-album"></i> Quest Tersedia</button>
        <button class="tab-link" onclick="openTab(event, 'createQuest')"><i class="bi bi-plus-circle-dotted"></i> Buat & Kelola</button>
        <button class="tab-link" onclick="openTab(event, 'completedQuests')"><i class="bi bi-archive-fill"></i> Riwayat</button>
    </div>

    {{-- ========================================================== --}}
    {{-- Konten Tab 1: Quest Saya
    {{-- ========================================================== --}}
    <div id="myQuests" class="tab-content active">
        
        <div class="section-header">
            <i class="bi bi-person-check-fill"></i>
            <h2 class="section-title">Quest Aktif</h2>
        </div>
        
        <div class="glass-card manage-quest-wrapper" data-wrapper="my-quests">
            @forelse ($myQuests as $log)
            
            <div class="quest-card-inner status-{{ $log->status }}">
                <div class="quest-info">
                    <h3>{{ $log->quest->title }}</h3>
                    
                    <div class="quest-meta">
                        <span><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($log->quest->frequency) }}</span>
                        <span><i class="bi bi-bar-chart-line"></i> Kesulitan: {{ ucfirst($log->quest->difficulty) }}</span>
                    </div>

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
                    
                    @if($log->status == 'pending_review' && $log->submission_notes)
                        <div class="submission-notes user">
                            <strong>Catatan Anda:</strong>
                            <p>{{ $log->submission_notes }}</p>
                        </div>
                    @endif
                    
                    @if($log->status == 'rejected' && $log->admin_notes)
                        <div class="submission-notes admin-rejected">
                            <strong>Catatan Admin (Ditolak):</strong>
                            <p>{{ $log->admin_notes }}</p>
                        </div>
                    @endif

                </div>
                <div class="quest-actions">
                    
                    @if (!$log->quest->is_admin_quest)
                        <form action="{{ route('quests.complete', $log->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Selesaikan</button>
                        </form>
                    
                    @else
                        @if ($log->status == 'active')
                            <button type="button" 
                                    class="btn btn-primary btn-submit-quest" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#submissionModal"
                                    data-submit-url="{{ route('quests.submit', $log->id) }}">
                                <i class="bi bi-cloud-upload-fill"></i> Kirim Bukti
                            </button>
                        
                        @elseif ($log->status == 'pending_review')
                            <button type="button" class="btn btn-warning" disabled>
                                <i class="bi bi-hourglass-split"></i> Menunggu Review
                            </button>
                        
                        @elseif ($log->status == 'rejected')
                             <button type="button" 
                                    class="btn btn-danger btn-submit-quest" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#submissionModal"
                                    data-submit-url="{{ route('quests.submit', $log->id) }}">
                                <i class="bi bi-cloud-upload-fill"></i> Kirim Ulang
                            </button>
                        @endif
                    @endif

                    <form action="{{ route('quests.cancel', $log->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="empty-state-text">Anda belum mengambil quest apapun. Kunjungi tab "Quest Tersedia" untuk memulai!</p>
            @endforelse

            @if($myQuests->hasPages())
            <div class="quest-pagination-container" data-section="my-quests">
                {{ $myQuests->fragment('myQuests')->links(null, ['pageName' => 'myQuests_page']) }}
            </div>
            @endif
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- Konten Tab 2: Quest Tersedia
    {{-- ========================================================== --}}
    <div id="availableQuests" class="tab-content">
        <div class="section-header">
            <i class="bi bi-patch-check-fill"></i>
            <h2 class="section-title">Quest Resmi (Admin)</h2>
        </div>
        
        <div class="glass-card manage-quest-wrapper" data-wrapper="admin-quests">
            @forelse ($adminQuests as $quest)
            <div class="quest-card-inner">
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

            @if($adminQuests->hasPages())
            <div class="quest-pagination-container" data-section="admin-quests">
                {{ $adminQuests->fragment('availableQuests')->links(null, ['pageName' => 'adminQuests_page']) }}
            </div>
            @endif
        </div>

        
        <div class="section-header" style="margin-top: 3rem;">
            <i class="bi bi-person-lines-fill"></i>
            <h2 class="section-title">Quest Pribadi Anda (Tersedia)</h2>
        </div>
        
        <div class="glass-card manage-quest-wrapper" data-wrapper="personal-quests">
            @forelse ($personalQuests as $quest) 
            <div class="quest-card-inner">
                <div class="quest-info">
                    <h3>{{ $quest->title }}</h3>
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
            <p class="empty-state-text">Tidak ada quest pribadi Anda yang aktif dan tersedia untuk diambil saat ini.</p>
            @endforelse

            @if($personalQuests->hasPages())
            <div class="quest-pagination-container" data-section="personal-quests">
                {{ $personalQuests->fragment('availableQuests')->links(null, ['pageName' => 'personalQuests_page']) }}
            </div>
            @endif
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- Konten Tab 3: Buat Quest Sendiri
    {{-- ========================================================== --}}
    <div id="createQuest" class="tab-content">
        <div class="section-header">
            <i class="bi bi-pencil-square"></i>
            <h2 class="section-title">Buat Quest Kustom</h2>
        </div>
        
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
                 <p class="form-hint">Quest 'Sekali Jalan' akan langsung masuk riwayat setelah selesai.<br>
                    Hanya quest 'Harian' dan 'Mingguan' yang akan muncul di daftar "Kelola" di bawah.</p>
                
                 <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Simpan Quest</button>
            </form>
        </div>

        <div class="section-header" style="margin-top: 3rem;">
            <i class="bi bi-gear-fill"></i>
            <h2 class="section-title">Kelola Quest Berulang</h2>
        </div>

        <div class="glass-card manage-quest-wrapper" data-wrapper="manage-quests">
            
            @forelse ($myPersonalQuests as $quest)
            <div class="quest-card-inner {{ !$quest->is_active ? 'paused' : '' }}">
                <div class="quest-info">
                    <h3>{{ $quest->title }}</h3>
                    
                    <div class="quest-meta">
                        <span><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($quest->frequency) }}</span>
                        <span><i class="bi bi-bar-chart-line"></i> Kesulitan: {{ ucfirst($quest->difficulty) }}</span>
                    </div>
                    
                    <div class="quest-rewards">
                        <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                        <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                        @if($quest->stat_reward_type)
                        <span class="reward-tag">
                            <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $log->quest->stat_reward_value }} {{ ucfirst($log->quest->stat_reward_type) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="quest-actions">
                    
                    <form action="{{ route('quests.toggleStatus', $quest->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if($quest->is_active)
                            <button type="submit" class="btn btn-warning"><i class="bi bi-pause-fill"></i> Jeda</button>
                        @else
                            <button type="submit" class="btn btn-success"><i class="bi bi-play-fill"></i> Aktifkan</button>
                        @endif
                    </form>
                    
                    <form action="{{ route('quests.destroy', $quest->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-delete-quest">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="empty-state-text">Anda belum membuat quest harian atau mingguan.</p>
            @endforelse
            
            @if($myPersonalQuests->hasPages())
            <div class="quest-pagination-container" data-section="manage-quests">
                {{ $myPersonalQuests->fragment('createQuest')->links(null, ['pageName' => 'manage_page']) }}
            </div>
            @endif
            
        </div>
        
    </div>
    
    {{-- ========================================================== --}}
    {{-- Konten Tab 4: Riwayat Quest Selesai
    {{-- ========================================================== --}}
    <div id="completedQuests" class="tab-content">
         
         <div class="section-header">
            <i class="bi bi-archive-fill"></i>
            <h2 class="section-title">Quest Selesai</h2>
         </div>
         
        <div class="glass-card manage-quest-wrapper" data-wrapper="completed-quests">
            @forelse ($completedQuests as $log)
            <div class="quest-card-inner history">
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
            
            @if($completedQuests->hasPages())
            <div class="quest-pagination-container" data-section="completed">
                {{ $completedQuests->fragment('completedQuests')->links(null, ['pageName' => 'completed_page']) }}
            </div>
            @endif
        </div>
    </div>

</div>


{{-- ========================================================== --}}
{{-- MODAL UNTUK SUBMISSION QUEST ADMIN
{{-- ========================================================== --}}
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        
        <div class="modal-content glass-card">
            
            <div class="modal-header">
                <h5 class="modal-title page-title" id="submissionModalLabel">Kirim Bukti Quest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #fff;"></button>
            </div>
            
            <form id="submissionForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="submission_file">Upload Bukti (Maks: 5MB)</label>
                        <input type="file" id="submission_file" name="submission_file" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="submission_notes">Catatan (Opsional)</label>
                        <textarea id="submission_notes" name="submission_notes" rows="3" class="form-control" placeholder="Tulis catatan untuk admin di sini..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- 1. Panggil SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- 2. [PENTING] Pastikan baris ini ADA. --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- 3. Panggil file main.js kustom Anda --}}
<script src="{{ asset('js/quest/main.js') }}"></script>
@endpush