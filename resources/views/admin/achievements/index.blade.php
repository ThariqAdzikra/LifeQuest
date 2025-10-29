@extends('layouts.app')

@section('title', 'Kelola Achievements - Admin')

@push('styles')
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/admin/achievement.css') }}">
@endpush

@section('content')
<div class="quest-board-container">

    {{-- Header Halaman --}}
    <div class="page-header-admin">
        <div>
            <h1 class="page-title">
                <i class="bi bi-award-fill"></i>
                Kelola Achievements
            </h1>
            <p class="page-subtitle">Buat, edit, atau hapus title achievement.</p>
        </div>
        <a href="{{ route('admin.achievements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Baru
        </a>
    </div>

    @include('partials.admin_alerts') {{-- Include partials untuk notifikasi --}}

    {{-- Card Daftar Achievement --}}
    <div class="glass-card leaderboard-list">
        {{-- Header Tabel --}}
        <div class="leaderboard-header">
            <div class="header-icon">Ikon</div>
            <div class="header-title">Judul</div>
            <div class="header-desc">Deskripsi</div>
            <div class="header-actions">Aksi</div>
        </div>

        {{-- Body Tabel --}}
        @forelse ($achievements as $achievement)
            <div class="leaderboard-row">
                <div class="icon-col">
                    @if($achievement->icon_path)
                        <img src="{{ Storage::url($achievement->icon_path) }}" alt="Icon">
                    @else
                        <div class="icon-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                <div class="title-col">
                    {{ $achievement->title }}
                </div>
                <div class="desc-col">
                    {{ $achievement->description ?? '-' }}
                </div>
                <div class="actions-col">
                    <a href="{{ route('admin.achievements.edit', $achievement) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    {{-- Ganti onsubmit dengan class untuk JS --}}
                    <form action="{{ route('admin.achievements.destroy', $achievement) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-delete-achievement">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                Belum ada achievement yang dibuat.
            </div>
        @endforelse
    </div>

    {{-- Paginasi --}}
    <div class="pagination-links">
        {{ $achievements->links() }}
    </div>
</div>
@endsection

@push('scripts')
{{-- 1. Panggil SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- 2. Panggil file JS kustom Anda --}}
<script src="{{ asset('js/admin/achievement.js') }}"></script>
@endpush