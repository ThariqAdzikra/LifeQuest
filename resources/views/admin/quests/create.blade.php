{{-- Menggunakan layout utama Anda (yang berisi navigasi) --}}
@extends('layouts.app')

@section('content')
{{-- 
  Saya ambil style dari file CSS Anda (style.css) 
  agar tampilannya konsisten dengan halaman quest Anda.
--}}
<div class="quest-board-container" style="max-width: 1140px; margin: 0 auto; padding: 2rem;">

    <div class="section-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 0.75rem;">
        <i class="bi bi-pencil-square" style="font-size: 1.2rem; color: #00d4ff;"></i>
        <h2 class="section-title" style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; color: #e2e8f0; margin: 0;">
            Buat Quest Admin Baru
        </h2>
    </div>
    
    <div class="glass-card" style="padding: 2rem; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem;">
        
        {{-- Tampilkan Error Validasi jika ada --}}
        @if ($errors->any())
            <div style="background: rgba(220, 38, 38, 0.15); border: 1px solid rgba(220, 38, 38, 0.4); color: #f87171; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <strong>Oops! Ada yang salah:</strong>
                <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.quests.store') }}" method="POST">
            @csrf
            
            {{-- Judul --}}
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="title" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Judul Quest</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Kalahkan Raja Goblin" required 
                       style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0;">
            </div>
            
            {{-- Deskripsi --}}
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-control" placeholder="Deskripsikan quest..." 
                          style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0; font-family: 'Poppins', sans-serif;"></textarea>
            </div>
            
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                {{-- Kesulitan --}}
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="difficulty" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Kesulitan</label>
                    <select id="difficulty" name="difficulty" class="form-control" 
                            style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem;">
                        <option value="easy">Mudah</option>
                        <option value="medium">Sedang</option>
                        <option value="hard">Sulit</option>
                    </select>
                </div>
                {{-- Frekuensi --}}
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="frequency" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Frekuensi</label>
                    <select id="frequency" name="frequency" class="form-control" 
                            style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem;">
                        <option value="once">Sekali Jalan</option>
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                    </select>
                </div>
            </div>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0 1.5rem 0;">
            
            {{-- Reward (Manual Input) --}}
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="exp_reward" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Reward EXP</label>
                    <input type="number" id="exp_reward" name="exp_reward" class="form-control" value="0" required 
                           style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="gold_reward" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Reward Gold</label>
                    <input type="number" id="gold_reward" name="gold_reward" class="form-control" value="0" required 
                           style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0;">
                </div>
            </div>
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="stat_reward_type" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Reward Stat</label>
                    <select id="stat_reward_type" name="stat_reward_type" class="form-control" 
                            style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem;">
                        <option value="">Tidak Ada</option>
                        <option value="intelligence">Intelligence</option>
                        <option value="strength">Strength</option>
                        <option value="stamina">Stamina</option>
                        <option value="agility">Agility</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="stat_reward_value" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Jumlah Stat</label>
                    <input type="number" id="stat_reward_value" name="stat_reward_value" class="form-control" value="0" 
                           style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0;">
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0 1.5rem 0;">

            {{-- [TAMBAHAN BARU] Dropdown Achievement --}}
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="achievement_id" style="display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Hadiah Achievement (Title)</label>
                <select id="achievement_id" name="achievement_id" class="form-control" 
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #e2e8f0; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem;">
                    <option value="">Tidak ada</option>
                    @foreach ($achievements as $achievement)
                        {{-- Asumsi 'title' adalah nama achievement-nya --}}
                        <option value="{{ $achievement->id }}">{{ $achievement->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #00d4ff; color: #0a0e27; box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);">
                <i class="bi bi-save-fill"></i> Simpan Quest Admin
            </button>
        </form>
    </div>

</div>
@endsection