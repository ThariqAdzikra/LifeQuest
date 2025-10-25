<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke User (yang mengambil quest).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Quest (detail quest yang diambil).
     */
    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}