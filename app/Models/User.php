<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'batch_id',
        'student_id_number',
        'avatar_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(Recording::class, 'uploaded_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Recording::class, 'reviewed_by');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class)->latest();
    }

    public function lectureNotes(): HasMany
    {
        return $this->hasMany(LectureNote::class)->latest();
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class)->where('is_read', false)->latest();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if student can view lectures (must be verified or admin)
     */
    public function canAccessLectures(): bool
    {
        return $this->isAdmin() || ($this->isStudent() && $this->isVerified());
    }

    /**
     * Check if student can upload recordings (must be verified student or admin)
     */
    public function canUpload(): bool
    {
        return $this->isAdmin() || ($this->isStudent() && $this->isVerified());
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }

        return $initials ?: 'U';
    }
}
