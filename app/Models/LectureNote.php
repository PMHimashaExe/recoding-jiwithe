<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LectureNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'title',
        'note_type',
        'key_takeaways',
        'content',
        'student_notes',
    ];

    protected function casts(): array
    {
        return [
            'key_takeaways' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(LectureSession::class, 'session_id');
    }

    /**
     * @return array{label: string, color: string}
     */
    public function getNoteTypeBadgeAttribute(): array
    {
        return match ($this->note_type) {
            'cheat_sheet' => ['label' => '⚡ Quick Exam Cram Sheet', 'color' => 'amber'],
            'quiz_flashcards' => ['label' => '❓ Q&A & Flashcards', 'color' => 'cyan'],
            default => ['label' => '📚 Comprehensive Study Guide', 'color' => 'emerald'],
        };
    }
}
