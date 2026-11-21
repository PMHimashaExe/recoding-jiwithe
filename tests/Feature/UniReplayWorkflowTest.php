<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UniReplayWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the complete end-to-end hackathon workflow.
     */
    public function test_complete_hackathon_workflow(): void
    {
        Storage::fake('public');

        // Setup base batch & subject
        $batch = Batch::firstOrCreate(
            ['batch_name' => 'Batch 2026 - Data & Computing'],
            ['graduation_date' => '2026-12-15']
        );

        $otherBatch = Batch::firstOrCreate(
            ['batch_name' => 'Batch 2025 - Software Engineering'],
            ['graduation_date' => '2025-11-30']
        );

        $subject = Subject::firstOrCreate(
            ['subject_code' => 'SE301'],
            [
                'subject_name' => 'Software Engineering & Architecture',
                'description' => 'Distributed architectures and patterns',
                'icon' => 'code-2',
            ]
        );

        // Batch Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@unireplay.edu'],
            [
                'name' => 'Dr. Sarah Chen',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'verified',
                'batch_id' => $batch->id,
            ]
        );

        // 1. A new student registers
        $studentEmail = 'newstudent@unireplay.edu';
        $registerResponse = $this->post(route('register'), [
            'name' => 'Jordan Reed',
            'email' => $studentEmail,
            'student_id_number' => 'ST-2026-9901',
            'batch_id' => $batch->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => $studentEmail,
            'status' => 'pending',
            'role' => 'student',
        ]);

        $newStudent = User::where('email', $studentEmail)->first();

        // Pending student cannot access protected lectures
        $this->actingAs($newStudent)
            ->get(route('lectures.index'))
            ->assertRedirect(route('verification.notice'));

        // 2. Admin approves the student
        $approveStudentResponse = $this->actingAs($admin)
            ->post(route('admin.students.approve', $newStudent));

        $approveStudentResponse->assertSessionHas('success');
        $this->assertEquals('verified', $newStudent->fresh()->status);

        // 3. The verified student logs in and accesses dashboard
        $this->actingAs($newStudent->fresh())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Jordan Reed')
            ->assertSee('Verified');

        // 4. The student uploads a lecture recording
        $uploadResponse = $this->actingAs($newStudent->fresh())
            ->post(route('upload.store'), [
                'subject_id' => $subject->id,
                'batch_id' => $batch->id,
                'lecture_date' => '2026-11-20',
                'lecture_number' => 8,
                'part_number' => 1,
                'title' => 'Event Sourcing with Kafka in Production',
                'description' => 'Complete walkthrough of state transitions and replay mechanisms.',
                'resource_title' => 'Architecture Cheatsheet',
                'resource_link' => 'https://docs.unireplay.edu/events',
            ]);

        $uploadResponse->assertRedirect(route('my-uploads'));

        // Upload is pending review and NOT publicly available yet
        $this->assertDatabaseHas('recordings', [
            'part_number' => 1,
            'status' => 'pending',
            'uploaded_by' => $newStudent->id,
        ]);

        $recording = Recording::where('uploaded_by', $newStudent->id)
            ->where('part_number', 1)
            ->first();

        // 5. The relevant batch admin sees the pending upload
        $adminDashboard = $this->actingAs($admin)
            ->get(route('admin.recordings.index', ['status' => 'pending', 'scope' => 'my_batch']));

        $adminDashboard->assertOk()
            ->assertSee('Event Sourcing with Kafka in Production')
            ->assertSee('Part 1');

        // 6. Admin approves the recording
        $approveRecResponse = $this->actingAs($admin)
            ->post(route('admin.recordings.approve', $recording));

        $approveRecResponse->assertRedirect(route('admin.recordings.index', ['status' => 'pending']));
        $this->assertEquals('approved', $recording->fresh()->status);

        // 7. Another verified student from a DIFFERENT batch logs in
        $otherStudent = User::firstOrCreate(
            ['email' => 'elena@student.unireplay.edu'],
            [
                'name' => 'Elena Rostova',
                'password' => bcrypt('password'),
                'role' => 'student',
                'status' => 'verified',
                'batch_id' => $otherBatch->id,
            ]
        );

        // 8. That student searches for and finds the published recording (Cross-batch access!)
        $searchResponse = $this->actingAs($otherStudent)
            ->get(route('lectures.index', ['search' => 'Kafka in Production']));

        $searchResponse->assertOk()
            ->assertSee('Event Sourcing with Kafka in Production');

        // 9. The student opens and watches the lecture and accesses resources
        $viewResponse = $this->actingAs($otherStudent)
            ->get(route('lectures.show', $recording->session));

        $viewResponse->assertOk()
            ->assertSee('Event Sourcing with Kafka in Production')
            ->assertSee('Architecture Cheatsheet')
            ->assertSee('Jordan Reed');
    }

    /**
     * Test duplicate part constraint prevents duplicate part numbers for the same session.
     */
    public function test_duplicate_part_number_is_prevented(): void
    {
        $batch = Batch::create([
            'batch_name' => 'Batch 2026 - Data & Computing',
            'graduation_date' => '2026-12-15',
        ]);

        $subject = Subject::create([
            'subject_code' => 'CS204',
            'subject_name' => 'Database Systems',
        ]);

        $student = User::create([
            'name' => 'Alex Rivera',
            'email' => 'alex@student.unireplay.edu',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'verified',
            'batch_id' => $batch->id,
        ]);

        $session = LectureSession::create([
            'subject_id' => $subject->id,
            'batch_id' => $batch->id,
            'lecture_date' => '2026-11-20',
            'lecture_number' => 20,
            'title' => 'Unique Constraint Test Session',
        ]);

        Recording::create([
            'session_id' => $session->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'status' => 'approved',
            'uploaded_by' => $student->id,
        ]);

        // Attempting to upload part 1 again for the same session
        $response = $this->actingAs($student)
            ->from(route('upload.create'))
            ->post(route('upload.store'), [
                'subject_id' => $subject->id,
                'batch_id' => $batch->id,
                'lecture_date' => '2026-11-20',
                'lecture_number' => 20,
                'part_number' => 1,
                'title' => 'Unique Constraint Test Session',
            ]);

        $response->assertSessionHasErrors('part_number');
    }

    /**
     * Test uploading with a Google Drive share link and playback embedding.
     */
    public function test_student_can_upload_recording_with_google_drive_link(): void
    {
        $batch = Batch::firstOrCreate(
            ['batch_name' => 'Batch 2026 - Data & Computing'],
            ['graduation_date' => '2026-12-15']
        );

        $subject = Subject::firstOrCreate(
            ['subject_code' => 'SE302'],
            ['subject_name' => 'Cloud Native Engineering', 'icon' => 'cloud']
        );

        $student = User::create([
            'name' => 'Drive Tester',
            'email' => 'drivetester@unireplay.edu',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'verified',
            'batch_id' => $batch->id,
        ]);

        $driveUrl = 'https://drive.google.com/file/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms/view?usp=sharing';

        $uploadResponse = $this->actingAs($student)->post(route('upload.store'), [
            'subject_id' => $subject->id,
            'batch_id' => $batch->id,
            'lecture_date' => '2026-11-20',
            'lecture_number' => 5,
            'part_number' => 1,
            'title' => 'Kubernetes Clustering Lecture',
            'drive_url' => $driveUrl,
        ]);

        $uploadResponse->assertRedirect(route('my-uploads'));

        $recording = Recording::where('uploaded_by', $student->id)->latest()->first();
        $this->assertNotNull($recording);
        $this->assertEquals('1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms', $recording->drive_file_id);
        $this->assertTrue($recording->isDriveVideo());
        $this->assertEquals('https://drive.google.com/file/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms/preview', $recording->drive_embed_url);

        // Approve and verify show page embeds drive iframe
        $recording->update(['status' => 'approved']);
        $showResponse = $this->actingAs($student)->get(route('lectures.show', $recording->session));
        $showResponse->assertOk();
        $showResponse->assertSee('drive.google.com/file/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms/preview');
    }
}
