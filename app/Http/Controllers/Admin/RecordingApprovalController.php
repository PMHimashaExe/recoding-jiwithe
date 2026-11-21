<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Batch;
use App\Models\Recording;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecordingApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $admin = $request->user();
        $status = $request->input('status', 'pending');
        $batchId = $request->input('batch_id');
        $scope = $request->input('scope', $admin->batch_id ? 'my_batch' : 'all');

        $query = Recording::with(['session.subject', 'session.batch', 'uploader', 'reviewer']);

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($scope === 'my_batch' && $admin->batch_id) {
            $query->whereHas('session', function ($q) use ($admin) {
                $q->where('batch_id', $admin->batch_id);
            });
        } elseif ($batchId) {
            $query->whereHas('session', function ($q) use ($batchId) {
                $q->where('batch_id', $batchId);
            });
        }

        $recordings = $query->latest()->paginate(12)->withQueryString();

        $batches = Batch::orderBy('batch_name')->get();

        $counts = [
            'pending' => Recording::where('status', 'pending')
                ->when($scope === 'my_batch' && $admin->batch_id, function ($q) use ($admin) {
                    $q->whereHas('session', function ($sq) use ($admin) {
                        $sq->where('batch_id', $admin->batch_id);
                    });
                })->count(),
            'approved' => Recording::where('status', 'approved')->count(),
            'rejected' => Recording::where('status', 'rejected')->count(),
        ];

        return view('admin.recordings.index', compact(
            'recordings',
            'batches',
            'status',
            'batchId',
            'scope',
            'counts',
            'admin'
        ));
    }

    public function show(Recording $recording): View
    {
        $recording->load(['session.subject', 'session.batch', 'session.resources', 'uploader', 'reviewer']);

        return view('admin.recordings.show', compact('recording'));
    }

    public function approve(Request $request, Recording $recording): RedirectResponse
    {
        $recording->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Send in-app notification to the uploader
        AppNotification::create([
            'user_id' => $recording->uploaded_by,
            'title' => 'Recording Approved & Published! 🎥',
            'message' => "Your upload '{$recording->session->title}' (Part {$recording->part_number}) has been approved by administrator ".auth()->user()->name.' and is now available in the university lecture library.',
            'type' => 'recording_approved',
            'link' => route('lectures.show', ['session' => $recording->session_id, 'part' => $recording->id]),
            'is_read' => false,
        ]);

        return redirect()->route('admin.recordings.index', ['status' => 'pending'])
            ->with('success', "Recording '{$recording->session->title}' (Part {$recording->part_number}) approved and published.");
    }

    public function reject(Request $request, Recording $recording): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $recording->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send in-app notification to the uploader with the rejection reason
        AppNotification::create([
            'user_id' => $recording->uploaded_by,
            'title' => 'Recording Upload Rejected',
            'message' => "Your upload '{$recording->session->title}' (Part {$recording->part_number}) was rejected. Reason: {$validated['rejection_reason']}",
            'type' => 'recording_rejected',
            'link' => route('my-uploads', ['status' => 'rejected']),
            'is_read' => false,
        ]);

        return redirect()->route('admin.recordings.index')
            ->with('warning', "Recording Part {$recording->part_number} rejected. Rejection reason has been delivered to the student.");
    }
}
