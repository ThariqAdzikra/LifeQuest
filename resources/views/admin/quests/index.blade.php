@extends('layouts.app')

@section('title', 'Kelola Quest Admin - LifeQuest')

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
            <p class="page-subtitle">Buat, edit, atau hapus quest resmi untuk player.</p>
        </div>
        
        {{-- Tombol 'Buat Baru' --}}
        <a href="{{ route('admin.quests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
    </div>

    @include('partials.admin_alerts') {{-- Tampilkan notifikasi (jika ada) --}}

    {{-- Wrapper utama UNTUK DAFTAR QUEST --}}
    <div class="glass-card manage-quest-wrapper">
        
        @forelse ($adminQuests as $quest)
        <div class="quest-card-inner"> 
            <div class="quest-info">
                <h3>{{ $quest->title }}</h3>
                
                <div class="quest-meta">
                    <span><i class="bi bi-clock"></i> Frekuensi: {{ ucfirst($quest->frequency) }}</span>
                    <span><i class="bi bi-bar-chart-line"></i> Kesulitan: {{ ucfirst($quest->difficulty) }}</span>
                </div>
                
                @if($quest->description)
                    <p>{{ $quest->description }}</p>
                @endif
                
                <div class="quest-rewards">
                    <span class="reward-tag"><i class="bi bi-star-fill"></i> {{ $quest->exp_reward }} EXP</span>
                    <span class="reward-tag"><i class="bi bi-coin"></i> {{ $quest->gold_reward }} Gold</span>
                    @if($quest->stat_reward_type)
                    <span class="reward-tag">
                        <i class="{{ getStatIcon($quest->stat_reward_type) }}"></i> +{{ $quest->stat_reward_value }} {{ ucfirst($quest->stat_reward_type) }}
                    </span>
                    @endif
                    
                    @if($quest->achievement)
                    <span class="reward-tag reward-tag-achievement">
                         @if($quest->achievement->icon_path)
                         <img src="{{ asset('storage/' . $quest->achievement->icon_path) }}" alt="icon" class="achievement-icon">
                         @else
                         <i class="bi bi-award-fill"></i> 
                         @endif
                        Title: {{ $quest->achievement->title }}
                    </span>
                    @endif
                </div>
            </div>
            
            {{-- Tombol Aksi Admin --}}
            <div class="quest-actions">
                {{-- TOMBOL EDIT --}}
                <button type="button" class="btn btn-warning btn-edit-quest" data-quest-id="{{ $quest->id }}">
                    <i class="bi bi-pencil-fill"></i> Edit
                </button>
                
                {{-- TOMBOL HAPUS --}}
                <form action="{{ route('admin.quests.destroy', $quest->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-delete-quest">
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <p class="empty-state-text"> 
            Anda belum membuat quest admin.
        </p>
        @endforelse

        {{-- [PASTIKAN TIDAK ADA PAGINATION DI DALAM SINI] --}}

    </div> {{-- PENUTUP .glass-card.manage-quest-wrapper --}}

    
    {{-- 
    ================================================
    [INI BLOK YANG BENAR]
    KITA LETAKKAN DI LUAR CARD, SEBAGAI BLOK SENDIRI
    ================================================
    --}}
    <div class="quest-pagination-container">
        
        {{-- BAGIAN 2: TOMBOL ANGKA (Ini yang hilang) --}}
        {{ $adminQuests->links() }} 

    </div>
    {{-- [AKHIR DARI BLOK PAGINATION] --}}


</div>

{{-- MODAL EDIT QUEST --}}
<div class="modal-overlay" id="editQuestModal">
    {{-- ... (Isi modal tidak berubah) ... --}}
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h2><i class="bi bi-pencil-square"></i> Edit Quest Admin</h2>
            <button type="button" class="modal-close-btn" id="closeModalBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="editQuestForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-body-custom">
                {{-- Judul --}}
                <div class="form-group">
                    <label for="edit_title">Judul Quest</label>
                    <input type="text" id="edit_title" name="title" class="form-control" required>
                </div>
                
                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="edit_description">Deskripsi</label>
                    <textarea id="edit_description" name="description" rows="3" class="form-control"></textarea>
                </div>
                
                <div class="form-grid">
                    {{-- Kesulitan --}}
                    <div class="form-group">
                        <label for="edit_difficulty">Kesulitan</label>
                        <select id="edit_difficulty" name="difficulty" class="form-control">
                            <option value="easy">Mudah</option>
                            <option value="medium">Sedang</option>
                            <option value="hard">Sulit</option>
                        </select>
                    </div>
                    
                    {{-- Frekuensi --}}
                    <div class="form-group">
                        <label for="edit_frequency">Frekuensi</label>
                        <select id="edit_frequency" name="frequency" class="form-control">
                            <option value="once">Sekali Jalan</option>
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                        </select>
                    </div>
                </div>
                
                <hr class="form-divider">
                
                {{-- Reward --}}
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_exp_reward">Reward EXP</label>
                        <input type="number" id="edit_exp_reward" name="exp_reward" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_gold_reward">Reward Gold</label>
                        <input type="number" id="edit_gold_reward" name="gold_reward" class="form-control" min="0" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_stat_reward_type">Reward Stat</label>
                        <select id="edit_stat_reward_type" name="stat_reward_type" class="form-control">
                            <option value="">Tidak Ada</option>
                            <option value="intelligence">Intelligence</option>
                            <option value="strength">Strength</option>
                            <option value="stamina">Stamina</option>
                            <option value="agility">Agility</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_stat_reward_value">Jumlah Stat</label>
                        <input type="number" id="edit_stat_reward_value" name="stat_reward_value" class="form-control" min="0">
                    </div>
                </div>
                
                <hr class="form-divider">
                
                {{-- Achievement --}}
                <div class="form-group">
                    <label for="edit_achievement_id">Hadiah Achievement (Title)</label>
                    <select id="edit_achievement_id" name="achievement_id" class="form-control">
                        <option value="">Memuat...</option>
                    </select>
                </div>
            </div>
            
            <div class="modal-footer-custom">
                <button type="button" class="btn btn-secondary-glass" id="cancelModalBtn">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save-fill"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin/quest.js') }}"></script>
@endpush

{{-- Helper function --}}
@php
if (!function_exists('getStatIcon')) {
    function getStatIcon($statType) {
        $icons = [
            'intelligence' => 'bi bi-brain',
            'strength' => 'bi bi-person-arms-up',
            'stamina' => 'bi bi-lightning-charge-fill',
            'agility' => 'bi bi-wind',
        ];
        return $icons[$statType] ?? 'bi bi-question-circle';
    }
}
@endphp