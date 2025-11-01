@extends('layouts.app')

@section('title', 'Review Submissions - Admin')

@push('styles')
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/admin/submission.css') }}">
@endpush

@section('content')
<div class="quest-board-container">

    {{-- Header Halaman --}}
    <div class="page-header-admin">
        <div>
            <h1 class="page-title">
                <i class="bi bi-clipboard-check-fill"></i>
                Review Submission
            </h1>
            <p class="page-subtitle">Setujui atau tolak quest yang dikirim oleh player.</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert-success-glass">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-danger-glass">
            {{ session('error') }}
        </div>
    @endif

    {{-- Wrapper untuk daftar submission --}}
    <div class="glass-card manage-quest-wrapper">
        
        @forelse ($submissions as $log)
        {{-- Kartu Submission --}}
        <div class="quest-card-inner">
            
            <div class="quest-info">
                <h3>
                    {{ $log->quest->title }}
                </h3>
                
                <div class="quest-meta">
                    <span><i class="bi bi-person-fill"></i> User: {{ $log->user->name }}</span>
                    <span><i class="bi bi-calendar-event"></i> Dikirim: {{ $log->updated_at->format('d F Y, H:i') }}</span>
                </div>
                
                {{-- Catatan User --}}
                <div class="submission-notes-user">
                    <strong>Catatan User:</strong>
                    {{ $log->submission_notes ?? '(Tidak ada catatan)' }}
                </div>
                
                {{-- Link Lihat Bukti --}}
                @if($log->submission_file_path)
                <a href="{{ Storage::url($log->submission_file_path) }}" target="_blank" class="btn btn-primary mt-3">
                    <i class="bi bi-eye-fill"></i> Lihat Bukti
                </a>
                @endif
            </div>
            
            {{-- Tombol Aksi Admin --}}
            <div class="quest-actions">
                
                {{-- TOMBOL APPROVE --}}
                <form action="{{ route('admin.submissions.approve', $log->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
                
                {{-- TOMBOL REJECT --}}
                <form action="{{ route('admin.submissions.reject', $log->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-reject-submission">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </form>
            </div>
        </div>
        @empty
        {{-- Tampilan Kosong --}}
        <p class="empty-state-text">
            Tidak ada submission yang menunggu review.
        </p>
        @endforelse

        {{-- [PERUBAHAN] Link Paginasi Dihapus dari DALAM card --}}
        {{-- @if ($submissions->hasPages())
        <div class="pagination-links">
            {{ $submissions->links() }}
        </div>
        @endif --}}
        
    </div> {{-- Ini adalah penutup .glass-card --}}


    {{-- [PERUBAHAN BARU] Blok paginasi gaya Quest diletakkan DI LUAR card --}}
    @if ($submissions->hasPages())
        <div class="quest-pagination-container">
            {{-- Info "Showing..." --}}
            <div class="pagination-info">
                Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }} results
            </div>
            
            {{-- Link Paginasi --}}
            {{ $submissions->links() }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
{{-- 1. Panggil SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- 2. Panggil file JS kustom Anda --}}
<script src="{{ asset('js/admin/submission.js') }}"></script>
@endpush