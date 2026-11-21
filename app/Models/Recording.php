<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recording extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'part_number',
        'video_path',
        'drive_url',
        'drive_file_id',
        'original_filename',
        'status',
        'uploaded_by',
        'reviewed_by',
        'rejection_reason',
        'duration',
        'thumbnail_path',
    ];

    protected function casts(): array
    {
        return [
            'part_number' => 'integer',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(LectureSession::class, 'session_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDriveVideo(): bool
    {
        return ! empty($this->drive_file_id);
    }

    public function getDriveEmbedUrlAttribute(): ?string
    {
        if ($this->drive_file_id) {
            return "https://drive.google.com/file/d/{$this->drive_file_id}/preview";
        }

        return null;
    }

    public static function extractDriveFileId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);

        // Standard: https://drive.google.com/file/d/FILE_ID/...
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Query param: id=FILE_ID
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Direct File ID string
        if (preg_match('/^[a-zA-Z0-9_-]{25,60}$/', $url)) {
            return $url;
        }

        return null;
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }
}
