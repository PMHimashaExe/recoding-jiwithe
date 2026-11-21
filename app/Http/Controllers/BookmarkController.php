<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\LectureSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $bookmarkedSessions = LectureSession::whereHas('bookmarks', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->with(['subject', 'batch', 'approvedRecordings'])
            ->latest()
            ->paginate(9);

        return view('student.bookmarks', compact('bookmarkedSessions'));
    }

    public function toggle(Request $request, LectureSession $session): JsonResponse|RedirectResponse
    {
        $existing = Bookmark::where('user_id', auth()->id())
            ->where('session_id', $session->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
            $message = 'Removed from bookmarks';
        } else {
            Bookmark::create([
                'user_id' => auth()->id(),
                'session_id' => $session->id,
            ]);
            $bookmarked = true;
            $message = 'Saved to bookmarks';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'bookmarked' => $bookmarked,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
