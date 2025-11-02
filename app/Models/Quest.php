<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User (pembuat quest).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function logs()
    {
        return $this->hasMany(QuestLog::class);
    }
}