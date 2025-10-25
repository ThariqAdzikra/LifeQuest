<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (atau tentukan $fillable)
    protected $guarded = [];

    /**
     * Relasi ke User (pembuat quest).
     */
    public function creator()
    {
        // Asumsi 'creator_id' adalah foreign key ke 'id' di tabel 'users'
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Relasi ke QuestLog (siapa saja yang mengambil quest ini).
     */
    public function logs()
    {
        return $this->hasMany(QuestLog::class);
    }
}