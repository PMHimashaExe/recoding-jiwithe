<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_name',
        'graduation_date',
    ];

    protected function casts(): array
    {
        return [
            'graduation_date' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function lectureSessions(): HasMany
    {
        return $this->hasMany(LectureSession::class);
    }
}
