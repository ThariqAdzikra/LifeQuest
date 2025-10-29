@extends('layouts.app')

@section('content')
<div class="quest-board-container" style="max-width: 800px; margin: 0 auto; padding: 2rem;">

    <div class="section-header">
        <i class="bi bi-award-fill"></i>
        <h2 class="section-title">Buat Achievement Baru</h2>
    </div>
    
    <div class="glass-card" style="padding: 2rem;">
        @include('partials.admin_validation_errors') {{-- Include partials error --}}
        
        <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title">Judul Achievement (Title)</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="icon">Ikon Achievement (Opsional)</label>
                <input type="file" id="icon" name="icon" class="form-control" accept="image/*">
                <small class="form-text text-muted">Format: JPG, PNG, GIF, SVG. Maks 1MB.</small>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save-fill"></i> Simpan Achievement
            </button>
            <a href="{{ route('admin.achievements.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection