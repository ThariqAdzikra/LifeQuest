<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    /**
     * Atribut yang BOLEH diisi secara massal (mass assignable).
     * [PERBAIKAN] Mengganti $guarded dengan $fillable
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon_path',
        'condition',
        'key_name', 
    ];

    /**
     * Atribut yang harus di-cast.
     * Ini akan mengubah kolom 'condition' dari JSON string
     * menjadi PHP array secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        'condition' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('unlocked_at');
    }

    public function quests()
    {
        return $this->hasMany(Quest::class);
    }
}