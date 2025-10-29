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
        'icon_path', // <-- Pastikan ini ada
        'condition', // <-- Pastikan field lama Anda tetap ada jika masih dipakai
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

    /**
     * Relasi many-to-many ke User.
     * Ini untuk melihat user mana saja yang memiliki achievement ini.
     * Kita juga mengambil 'unlocked_at' dari pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('unlocked_at');
    }

    /**
     * [TAMBAHAN BARU] Relasi one-to-many ke Quest
     * (Achievement ini bisa menjadi hadiah untuk banyak Quest).
     */
    public function quests()
    {
        return $this->hasMany(Quest::class);
    }
}