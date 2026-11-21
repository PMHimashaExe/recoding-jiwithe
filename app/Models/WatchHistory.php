<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recording_id',
        'progress_seconds',
        'completed',
        'last_watched_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_seconds' => 'integer',
            'completed' => 'boolean',
            'last_watched_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recording(): BelongsTo
    {
        return $this->belongsTo(Recording::class);
    }
}
