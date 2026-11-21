<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_name',
        'subject_code',
        'description',
        'icon',
    ];

    public function lectureSessions(): HasMany
    {
        return $this->hasMany(LectureSession::class);
    }
}
