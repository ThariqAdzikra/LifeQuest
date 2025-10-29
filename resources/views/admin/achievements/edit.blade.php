@extends('layouts.app')

@section('title', 'Edit Achievement - Admin')

@push('styles')
{{-- Memanggil file CSS kustom --}}
<link rel="stylesheet" href="{{ asset('css/admin/achievement.css') }}">
@endpush

@section('content')
{{-- Container lebih sempit untuk form --}}
<div class="quest-board-container container-narrow">

    {{-- Header Halaman --}}
    <div class="page-header-admin">
        <div>
            <h1 class="page-title">
                <i class="bi bi-pencil-fill"></i>
                Edit Achievement
            </h1>
            <p class="page-subtitle">Edit detail untuk: {{ $achievement->title }}</p>
        </div>
        <a href="{{ route('admin.achievements.index') }}" class="btn btn-secondary-glass">
            <i class="bi bi-arrow-left"></i> Batal
        </a>
    </div>
    
    {{-- Card Form --}}
    <div class="glass-card">
        @include('partials.admin_validation_errors') {{-- Include partials error --}}
        
        <form action="{{ route('admin.achievements.update', $achievement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Judul Achievement (Title)</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $achievement->title) }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $achievement->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="icon">Ikon Achievement (Opsional)</label>
                 @if($achievement->icon_path)
                 {{-- Gunakan class untuk styling preview --}}
                 <div class="current-icon-preview">
                     <img src="{{ Storage::url($achievement->icon_path) }}" alt="Current Icon">
                     <small class="d-block">Ikon saat ini. Upload baru untuk mengganti.</small>
                 </div>
                 @endif
                <input type="file" id="icon" name="icon" class="form-control" accept="image/*">
                <small class="form-text">Format: JPG, PNG, GIF, SVG. Maks 1MB.</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="bi bi-save-fill"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection