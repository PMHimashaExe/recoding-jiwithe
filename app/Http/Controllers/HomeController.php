<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Subject;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'recordings_count' => Recording::where('status', 'approved')->count(),
            'students_count' => User::where('role', 'student')->where('status', 'verified')->count(),
            'subjects_count' => Subject::count(),
            'batches_count' => Batch::count(),
        ];

        $featuredSessions = LectureSession::with(['subject', 'batch', 'approvedRecordings'])
            ->whereHas('approvedRecordings')
            ->latest('lecture_date')
            ->take(6)
            ->get();

        $subjects = Subject::withCount(['lectureSessions as sessions_count' => function ($query) {
            $query->whereHas('approvedRecordings');
        }])->get();

        return view('home', compact('stats', 'featuredSessions', 'subjects'));
    }
}
