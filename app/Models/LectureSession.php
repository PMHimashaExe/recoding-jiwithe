<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LectureSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'batch_id',
        'lecture_date',
        'lecture_number',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'lecture_date' => 'date',
            'lecture_number' => 'integer',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class, 'session_id')->orderBy('part_number');
    }

    public function approvedRecordings(): HasMany
    {
        return $this->hasMany(Recording::class, 'session_id')
            ->where('status', 'approved')
            ->orderBy('part_number');
    }

    public function pendingRecordings(): HasMany
    {
        return $this->hasMany(Recording::class, 'session_id')
            ->where('status', 'pending')
            ->orderBy('part_number');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'session_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'session_id');
    }

    public function lectureNotes(): HasMany
    {
        return $this->hasMany(LectureNote::class, 'session_id');
    }

    public function userNote(?User $user): ?LectureNote
    {
        if (! $user) {
            return null;
        }

        return $this->lectureNotes()->where('user_id', $user->id)->latest()->first();
    }

    public function isBookmarkedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}
