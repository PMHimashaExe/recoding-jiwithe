<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'title',
        'file_path',
        'type',
        'external_url',
        'file_size',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(LectureSession::class, 'session_id');
    }

    public function getIconAttribute(): string
    {
        return match (strtolower($this->type)) {
            'pdf' => 'file-text',
            'ppt', 'pptx' => 'presentation',
            'zip', 'rar' => 'archive',
            'link' => 'external-link',
            default => 'file',
        };
    }
}
