<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $batchId = $request->input('batch_id');
        $search = $request->input('search');

        $query = User::with('batch')
            ->where('role', 'student');

        if ($status && in_array($status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('student_id_number', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        $batches = Batch::orderBy('batch_name')->get();

        $counts = [
            'pending' => User::where('role', 'student')->where('status', 'pending')->count(),
            'verified' => User::where('role', 'student')->where('status', 'verified')->count(),
            'rejected' => User::where('role', 'student')->where('status', 'rejected')->count(),
        ];

        return view('admin.students.index', compact('students', 'batches', 'status', 'batchId', 'search', 'counts'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        if (! $user->isStudent()) {
            abort(400, 'User is not a student.');
        }

        $user->update([
            'status' => 'verified',
        ]);

        // Send in-app notification to the student
        AppNotification::create([
            'user_id' => $user->id,
            'title' => 'Student Account Verified! 🎉',
            'message' => "Congratulations {$user->name}! Your student account has been approved by administrator ".auth()->user()->name.'. You can now browse all published lectures and upload lecture recordings.',
            'type' => 'account_approved',
            'link' => route('dashboard'),
            'is_read' => false,
        ]);

        return back()->with('success', "Student {$user->name} has been verified successfully.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if (! $user->isStudent()) {
            abort(400, 'User is not a student.');
        }

        $reason = $request->input('reason', 'Verification details could not be matched with university registrar records.');

        $user->update([
            'status' => 'rejected',
        ]);

        // Send in-app notification explaining rejection
        AppNotification::create([
            'user_id' => $user->id,
            'title' => 'Student Verification Declined',
            'message' => "Your registration request was declined by the university administrator. Reason: {$reason}",
            'type' => 'account_rejected',
            'link' => route('verification.notice'),
            'is_read' => false,
        ]);

        return back()->with('warning', "Student registration for {$user->name} has been rejected.");
    }
}
