<?php

namespace App\Models;

// 1. Mengaktifkan (uncomment) baris ini
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Achievement; // Pastikan ini ada
use App\Models\Quest; // Pastikan ini ada
use App\Models\QuestLog; // Pastikan ini ada

// 2. Menambahkan "implements MustVerifyEmail"
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // <-- TAMBAHKAN INI
        
        // --- TAMBAHAN STATS ---
        'exp',
        'gold',
        'intelligence',
        'strength',
        'stamina',
        'agility',
        // --- AKHIR TAMBAHAN ---
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // <-- TAMBAHKAN INI
        ];
    }

    // --- RELASI QUEST (SUDAH BENAR) ---

    /**
     * Relasi ke Quest (Quest yang *dibuat* oleh user ini).
     */
    public function quests()
    {
        return $this->hasMany(Quest::class, 'creator_id');
    }

    /**
     * Relasi ke QuestLog (Quest yang *diambil* oleh user ini).
     */
    public function questLogs()
    {
        return $this->hasMany(QuestLog::class);
    }
    
    // --- RELASI ACHIEVEMENT (SUDAH BENAR) ---

    /**
     * Relasi many-to-many ke Achievement (Achievement yang *dimiliki* user).
     */
    public function achievements()
    {
        // Menentukan nama pivot table 'user_achievements'
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at') // Mengambil 'unlocked_at'
                    ->orderBy('user_achievements.unlocked_at', 'desc'); // Urutkan dari yg terbaru
    }

    // ==========================================================
    // --- PENYESUAIAN (TAMBAHAN UNTUK HITUNG LEVEL OTOMATIS) ---
    // ==========================================================

    /**
     * Tambahkan 'level' ke output JSON/array.
     * Ini membuat $user->level bisa langsung dipakai di Blade.
     */
    protected $appends = ['level'];

    /**
     * Accessor untuk menghitung Level berdasarkan EXP.
     * Ini adalah logika yang kita pakai di halaman Stats & Leaderboard.
     */
    public function getLevelAttribute()
    {
        $exp = $this->attributes['exp'] ?? 0;
        
        if ($exp <= 0) {
            return 1;
        }
        
        // Rumus: 100 * (level-1)^2 = exp
        // (level-1)^2 = exp / 100
        // level-1 = sqrt(exp / 100)
        // level = floor(sqrt(exp / 100)) + 1
        return floor(pow($exp / 100, 0.5)) + 1;
    }

    // ==========================================================
    // --- PENYESUAIAN (TAMBAHAN UNTUK ADMIN) ---
    // ==========================================================
    
    /**
     * Helper function untuk mengecek apakah user adalah admin.
     * [PERBAIKAN] Ini adalah baris yang diperbaiki
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}