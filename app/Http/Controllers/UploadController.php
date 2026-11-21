<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Batch;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function create(): View
    {
        $subjects = Subject::orderBy('subject_name')->get();
        $batches = Batch::orderBy('batch_name')->get();

        return view('student.upload', compact('subjects', 'batches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'lecture_date' => ['required', 'date', 'before_or_equal:today'],
            'lecture_number' => ['required', 'integer', 'min:1'],
            'part_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'drive_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,mkv', 'max:512000'], // 500MB max for local demo
            'use_sample_video' => ['nullable', 'boolean'],
            'resource_title' => ['nullable', 'string', 'max:255'],
            'resource_file' => ['nullable', 'file', 'mimes:pdf,ppt,pptx,zip,rar,doc,docx', 'max:51200'],
            'resource_link' => ['nullable', 'url', 'max:500'],
        ]);

        // Find or create the lecture session for this subject, batch, lecture_date and lecture_number
        $session = LectureSession::where('subject_id', $validated['subject_id'])
            ->where('batch_id', $validated['batch_id'])
            ->whereDate('lecture_date', $validated['lecture_date'])
            ->where('lecture_number', $validated['lecture_number'])
            ->first();

        if (! $session) {
            $session = LectureSession::create([
                'subject_id' => $validated['subject_id'],
                'batch_id' => $validated['batch_id'],
                'lecture_date' => $validated['lecture_date'],
                'lecture_number' => $validated['lecture_number'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
            ]);
        }

        // Enforce the unique database constraint: session_id + part_number
        $duplicatePart = Recording::where('session_id', $session->id)
            ->where('part_number', $validated['part_number'])
            ->exists();

        if ($duplicatePart) {
            return back()->withInput()->withErrors([
                'part_number' => "Part {$validated['part_number']} already exists for Lecture #{$validated['lecture_number']} of this subject and batch. Please select another part number or verify the session.",
            ]);
        }

        // Handle video file storage or Google Drive link
        $driveFileId = null;
        $driveUrl = null;
        $videoPath = null;
        $originalFilename = 'Sample_Lecture_Demo.mp4';

        if (! empty($validated['drive_url'])) {
            $driveFileId = Recording::extractDriveFileId($validated['drive_url']);
            if (! $driveFileId) {
                return back()->withInput()->withErrors([
                    'drive_url' => 'Please provide a valid Google Drive sharing link (e.g., https://drive.google.com/file/d/.../view).',
                ]);
            }
            $driveUrl = $validated['drive_url'];
            $originalFilename = 'Google Drive Video';
        } elseif ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $file = $request->file('video_file');
            $originalFilename = $file->getClientOriginalName();
            $videoPath = $file->store('recordings', 'public');
        } else {
            // Default sample demo fallback
            $videoPath = 'recordings/sample-demo.mp4';
        }

        // Create the recording with status 'pending'
        $recording = Recording::create([
            'session_id' => $session->id,
            'part_number' => $validated['part_number'],
            'video_path' => $videoPath,
            'drive_url' => $driveUrl,
            'drive_file_id' => $driveFileId,
            'original_filename' => $originalFilename,
            'status' => 'pending',
            'uploaded_by' => auth()->id(),
            'duration' => '45:00', // Default estimated duration
            'thumbnail_path' => null,
        ]);

        // Handle optional resource attachment
        if (! empty($validated['resource_title'])) {
            $resourcePath = null;
            $resourceType = 'link';
            $fileSize = null;

            if ($request->hasFile('resource_file') && $request->file('resource_file')->isValid()) {
                $rFile = $request->file('resource_file');
                $ext = strtolower($rFile->getClientOriginalExtension());
                $resourceType = in_array($ext, ['ppt', 'pptx']) ? 'ppt' : ($ext === 'zip' ? 'zip' : 'pdf');
                $resourcePath = $rFile->store('resources', 'public');
                $fileSize = round($rFile->getSize() / 1024 / 1024, 1).' MB';
            }

            Resource::create([
                'session_id' => $session->id,
                'title' => $validated['resource_title'],
                'file_path' => $resourcePath,
                'type' => $resourceType,
                'external_url' => $validated['resource_link'] ?? null,
                'file_size' => $fileSize,
            ]);
        }

        // Notification for the uploader
        AppNotification::create([
            'user_id' => auth()->id(),
            'title' => 'Lecture Recording Submitted',
            'message' => "Your upload for '{$session->title}' (Part {$recording->part_number}) was submitted and is pending review by your batch administrator.",
            'type' => 'system',
            'link' => route('my-uploads'),
            'is_read' => false,
        ]);

        // Notification for the batch administrator
        $batchAdmin = User::where('role', 'admin')
            ->where('batch_id', $session->batch_id)
            ->first();

        // If no specific batch admin, notify any admin
        if (! $batchAdmin) {
            $batchAdmin = User::where('role', 'admin')->first();
        }

        if ($batchAdmin) {
            AppNotification::create([
                'user_id' => $batchAdmin->id,
                'title' => 'New Recording Requires Review',
                'message' => auth()->user()->name." uploaded '{$session->title}' (Part {$recording->part_number}) for Batch {$session->batch->batch_name}.",
                'type' => 'system',
                'link' => route('admin.recordings.index', ['status' => 'pending', 'batch_id' => $session->batch_id]),
                'is_read' => false,
            ]);
        }

        return redirect()->route('my-uploads')->with('success', 'Recording uploaded successfully! It is now pending approval by the batch administrator.');
    }

    public function myUploads(Request $request): View
    {
        $status = $request->input('status');

        $query = auth()->user()->uploads()
            ->with(['session.subject', 'session.batch', 'reviewer'])
            ->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $uploads = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => auth()->user()->uploads()->count(),
            'pending' => auth()->user()->uploads()->where('status', 'pending')->count(),
            'approved' => auth()->user()->uploads()->where('status', 'approved')->count(),
            'rejected' => auth()->user()->uploads()->where('status', 'rejected')->count(),
        ];

        return view('student.my-uploads', compact('uploads', 'stats', 'status'));
    }
}
