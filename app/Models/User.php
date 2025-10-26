<?php

namespace App\Models;

// 1. Mengaktifkan (uncomment) baris ini
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Achievement; // <-- TAMBAHKAN INI

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
        
        // --- TAMBAHAN STATS ---
        // Ini ditambahkan agar stats bisa diisi
        // saat registrasi atau di-update massal.
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
        ];
    }

    // --- TAMBAKAN RELASI UNTUK QUEST ---

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
    
    // --- TAMBAHAN RELASI BARU ---

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

    // --- AKHIR TAMBAHAN RELASI ---
}