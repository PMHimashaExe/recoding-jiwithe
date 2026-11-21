<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\LectureNote;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LectureNoteTest extends TestCase
{
    use RefreshDatabase;

    protected User $verifiedStudent;

    protected User $pendingStudent;

    protected LectureSession $session;

    protected function setUp(): void
    {
        parent::setUp();

        $batch = Batch::create([
            'batch_name' => 'Batch 2026 - CS',
            'graduation_date' => '2026-12-15',
        ]);

        $subject = Subject::create([
            'subject_code' => 'CS401',
            'subject_name' => 'Distributed Systems Architecture',
            'description' => 'Consensus protocols and microservices',
        ]);

        $this->session = LectureSession::create([
            'subject_id' => $subject->id,
            'batch_id' => $batch->id,
            'lecture_date' => '2026-11-20',
            'lecture_number' => 4,
            'title' => 'Raft Consensus Algorithm & Distributed State Machines',
            'description' => 'Detailed lecture covering leader election, log replication, and partition handling.',
        ]);

        $this->verifiedStudent = User::create([
            'name' => 'Kasun Perera',
            'email' => 'kasun@unireplay.edu',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'verified',
            'batch_id' => $batch->id,
        ]);

        // Approved recording for lecture to be visible
        Recording::create([
            'session_id' => $this->session->id,
            'part_number' => 1,
            'video_path' => 'recordings/demo.mp4',
            'status' => 'approved',
            'uploaded_by' => $this->verifiedStudent->id,
            'duration' => '45m',
        ]);

        $this->pendingStudent = User::create([
            'name' => 'Pending Student',
            'email' => 'pending@unireplay.edu',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'pending',
            'batch_id' => $batch->id,
        ]);
    }

    public function test_verified_student_can_generate_ai_lecture_note(): void
    {
        $response = $this->actingAs($this->verifiedStudent)
            ->postJson(route('lectures.notes.generate', $this->session), [
                'note_type' => 'summary',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'note' => [
                    'id',
                    'title',
                    'note_type',
                    'key_takeaways',
                    'content',
                ],
            ]);

        $this->assertDatabaseHas('lecture_notes', [
            'user_id' => $this->verifiedStudent->id,
            'session_id' => $this->session->id,
            'note_type' => 'summary',
        ]);
    }

    public function test_student_can_generate_different_note_formats(): void
    {
        $response = $this->actingAs($this->verifiedStudent)
            ->post(route('lectures.notes.generate', $this->session), [
                'note_type' => 'cheat_sheet',
            ]);

        $response->assertRedirect(route('lectures.show', $this->session));

        $this->assertDatabaseHas('lecture_notes', [
            'user_id' => $this->verifiedStudent->id,
            'session_id' => $this->session->id,
            'note_type' => 'cheat_sheet',
        ]);
    }

    public function test_student_can_save_personal_annotations(): void
    {
        $note = LectureNote::create([
            'user_id' => $this->verifiedStudent->id,
            'session_id' => $this->session->id,
            'title' => 'Test Study Guide',
            'note_type' => 'summary',
            'content' => 'Sample content',
        ]);

        $response = $this->actingAs($this->verifiedStudent)
            ->putJson(route('notes.update', $note), [
                'student_notes' => 'Lecturer hinted this will be in Question 2 of Mid-term.',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'student_notes' => 'Lecturer hinted this will be in Question 2 of Mid-term.',
            ]);

        $this->assertDatabaseHas('lecture_notes', [
            'id' => $note->id,
            'student_notes' => 'Lecturer hinted this will be in Question 2 of Mid-term.',
        ]);
    }

    public function test_student_can_download_note_as_markdown(): void
    {
        $note = LectureNote::create([
            'user_id' => $this->verifiedStudent->id,
            'session_id' => $this->session->id,
            'title' => 'Test Study Guide',
            'note_type' => 'summary',
            'content' => '# Comprehensive Study Note Content',
        ]);

        $response = $this->actingAs($this->verifiedStudent)
            ->get(route('notes.download', $note));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
    }

    public function test_pending_student_cannot_generate_lecture_notes(): void
    {
        $response = $this->actingAs($this->pendingStudent)
            ->postJson(route('lectures.notes.generate', $this->session), [
                'note_type' => 'summary',
            ]);

        $response->assertForbidden();
    }
}
