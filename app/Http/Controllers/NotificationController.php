<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, AppNotification $notification): JsonResponse|RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
