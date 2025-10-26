<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    /**
     * Atribut yang dijaga dari mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
}