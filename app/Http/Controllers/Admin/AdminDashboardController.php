<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $admin = $request->user();
        $adminBatch = $admin->batch;

        // Platform Statistics
        $totalVerifiedStudents = User::where('role', 'student')->where('status', 'verified')->count();
        $pendingStudentsCount = User::where('role', 'student')->where('status', 'pending')->count();
        $totalPublishedRecordings = Recording::where('status', 'approved')->count();

        // Pending recordings in admin's assigned batch vs all batches
        $pendingRecordingsBatchCount = Recording::where('status', 'pending')
            ->when($admin->batch_id, function ($q) use ($admin) {
                $q->whereHas('session', function ($sq) use ($admin) {
                    $sq->where('batch_id', $admin->batch_id);
                });
            })->count();

        $totalPendingRecordings = Recording::where('status', 'pending')->count();

        // Queues for immediate attention
        $pendingStudents = User::with('batch')
            ->where('role', 'student')
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $pendingRecordings = Recording::with(['session.subject', 'session.batch', 'uploader'])
            ->where('status', 'pending')
            ->when($admin->batch_id, function ($q) use ($admin) {
                $q->whereHas('session', function ($sq) use ($admin) {
                    $sq->where('batch_id', $admin->batch_id);
                });
            })
            ->latest()
            ->take(6)
            ->get();

        // Recent approvals activity
        $recentApprovals = Recording::with(['session.subject', 'session.batch', 'uploader', 'reviewer'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $batches = Batch::withCount(['students as verified_count' => function ($q) {
            $q->where('status', 'verified');
        }])->get();

        return view('admin.dashboard', compact(
            'admin',
            'adminBatch',
            'totalVerifiedStudents',
            'pendingStudentsCount',
            'totalPublishedRecordings',
            'pendingRecordingsBatchCount',
            'totalPendingRecordings',
            'pendingStudents',
            'pendingRecordings',
            'recentApprovals',
            'batches'
        ));
    }
}
