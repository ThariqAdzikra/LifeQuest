<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Achievement; 
use App\Models\Quest; 
use App\Models\QuestLog;

class User extends Authenticatable implements MustVerifyEmail
{
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
        'is_admin', 
        'exp',
        'gold',
        'intelligence',
        'strength',
        'stamina',
        'agility',
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
            'is_admin' => 'boolean', 
        ];
    }

    public function quests()
    {
        return $this->hasMany(Quest::class, 'creator_id');
    }

    public function questLogs()
    {
        return $this->hasMany(QuestLog::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at') 
                    ->orderBy('user_achievements.unlocked_at', 'desc'); 
    }

    protected $appends = ['level'];

    public function getLevelAttribute()
    {
        $exp = $this->attributes['exp'] ?? 0;
        
        if ($exp <= 0) {
            return 1;
        }
        return floor(pow($exp / 100, 0.5)) + 1;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}