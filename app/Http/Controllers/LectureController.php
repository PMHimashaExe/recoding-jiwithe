<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Subject;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LectureController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $subjectId = $request->input('subject_id');
        $batchId = $request->input('batch_id');
        $dateFilter = $request->input('date');
        $sort = $request->input('sort', 'newest');

        $query = LectureSession::query()
            ->with(['subject', 'batch', 'approvedRecordings.uploader'])
            ->whereHas('approvedRecordings');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('subject', function ($sq) use ($search) {
                        $sq->where('subject_name', 'like', "%{$search}%")
                            ->orWhere('subject_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        if ($dateFilter) {
            $query->whereDate('lecture_date', $dateFilter);
        }

        match ($sort) {
            'oldest' => $query->oldest('lecture_date'),
            'lecture_num' => $query->orderBy('lecture_number'),
            default => $query->latest('lecture_date')->latest('id'),
        };

        $sessions = $query->paginate(9)->withQueryString();

        $subjects = Subject::orderBy('subject_name')->get();
        $batches = Batch::orderBy('batch_name')->get();

        return view('lectures.index', compact('sessions', 'subjects', 'batches', 'search', 'subjectId', 'batchId', 'dateFilter', 'sort'));
    }

    public function show(Request $request, LectureSession $session): View
    {
        $session->load([
            'subject',
            'batch',
            'approvedRecordings.uploader',
            'resources',
        ]);

        $approvedRecordings = $session->approvedRecordings;

        if ($approvedRecordings->isEmpty() && ! auth()->user()->isAdmin()) {
            abort(404, 'No approved recordings found for this lecture session.');
        }

        // Active recording selection
        $partId = $request->query('part');
        $activeRecording = null;

        if ($partId) {
            $activeRecording = $approvedRecordings->firstWhere('id', $partId);
        }

        if (! $activeRecording) {
            $activeRecording = $approvedRecordings->first();
        }

        // Update or create watch history
        if ($activeRecording && auth()->check()) {
            WatchHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'recording_id' => $activeRecording->id,
                ],
                [
                    'last_watched_at' => now(),
                ]
            );
        }

        // Related sessions in the same subject
        $relatedSessions = LectureSession::with(['subject', 'batch', 'approvedRecordings'])
            ->where('subject_id', $session->subject_id)
            ->where('id', '!=', $session->id)
            ->whereHas('approvedRecordings')
            ->latest('lecture_date')
            ->take(3)
            ->get();

        $isBookmarked = auth()->check() ? $session->isBookmarkedBy(auth()->user()) : false;
        $existingNote = auth()->check() ? $session->userNote(auth()->user()) : null;
        $initialNoteData = $existingNote ? [
            'id' => $existingNote->id,
            'title' => $existingNote->title,
            'note_type' => $existingNote->note_type,
            'badge' => $existingNote->note_type_badge,
            'key_takeaways' => $existingNote->key_takeaways,
            'content' => $existingNote->content,
            'student_notes' => $existingNote->student_notes,
            'updated_at' => $existingNote->updated_at->diffForHumans(),
        ] : null;

        return view('lectures.show', compact('session', 'activeRecording', 'approvedRecordings', 'relatedSessions', 'isBookmarked', 'existingNote', 'initialNoteData'));
    }

    /**
     * Protected video streaming with HTTP 206 partial content support for smooth HTML5 seeking
     */
    public function stream(Recording $recording): BinaryFileResponse|StreamedResponse|Response
    {
        if (! auth()->user()->canAccessLectures() && ! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized to view this recording.');
        }

        // Try public storage path first
        $path = storage_path('app/public/'.$recording->video_path);

        // Fallback to sample demo video if actual video file not found on disk
        if (! file_exists($path)) {
            $samplePath = storage_path('app/public/recordings/sample-demo.mp4');
            if (file_exists($samplePath)) {
                $path = $samplePath;
            } else {
                abort(404, 'Video file not found.');
            }
        }

        $size = filesize($path);
        $file = fopen($path, 'rb');
        $start = 0;
        $end = $size - 1;

        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $range, $matches)) {
                $start = intval($matches[1]);
                if (! empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }

            fseek($file, $start);
            $length = $end - $start + 1;

            $headers = [
                'Content-Type' => 'video/mp4',
                'Content-Length' => $length,
                'Content-Range' => sprintf('bytes %d-%d/%d', $start, $end, $size),
                'Accept-Ranges' => 'bytes',
            ];

            return response()->stream(function () use ($file, $length) {
                $buffer = 1024 * 64;
                while (! feof($file) && $length > 0) {
                    $read = ($length > $buffer) ? $buffer : $length;
                    echo fread($file, $read);
                    flush();
                    $length -= $read;
                }
                fclose($file);
            }, 206, $headers);
        }

        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
        ];

        return response()->file($path, $headers);
    }
}
