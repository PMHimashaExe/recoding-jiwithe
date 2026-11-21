<?php

namespace App\Http\Controllers;

use App\Models\LectureSession;
use App\Models\Subject;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Continue watching items
        $continueWatching = WatchHistory::with(['recording.session.subject', 'recording.session.batch'])
            ->where('user_id', $user->id)
            ->where('completed', false)
            ->latest('last_watched_at')
            ->take(4)
            ->get();

        // Recently published approved lecture sessions
        $recentSessions = LectureSession::with(['subject', 'batch', 'approvedRecordings'])
            ->whereHas('approvedRecordings')
            ->latest('created_at')
            ->take(6)
            ->get();

        // Subjects with session count
        $subjects = Subject::withCount(['lectureSessions as sessions_count' => function ($query) {
            $query->whereHas('approvedRecordings');
        }])->get();

        // User's recent uploads
        $myRecentUploads = $user->uploads()
            ->with(['session.subject', 'session.batch'])
            ->latest()
            ->take(5)
            ->get();

        // Stats summary
        $stats = [
            'uploads_count' => $user->uploads()->count(),
            'approved_uploads' => $user->uploads()->where('status', 'approved')->count(),
            'bookmarks_count' => $user->bookmarks()->count(),
            'watched_count' => $user->watchHistories()->where('completed', true)->count(),
        ];

        return view('student.dashboard', compact(
            'user',
            'continueWatching',
            'recentSessions',
            'subjects',
            'myRecentUploads',
            'stats'
        ));
    }
}
