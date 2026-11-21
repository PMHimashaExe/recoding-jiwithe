<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isPending()) {
                return redirect()->route('verification.notice');
            }

            if ($user->isRejected()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your student account registration was rejected by the university administration. Please contact support.',
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $batches = Batch::orderBy('batch_name')->get();

        return view('auth.register', compact('batches'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'student_id_number' => ['required', 'string', 'max:50'],
            'batch_id' => ['required', 'exists:batches,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $colors = ['#7657F6', '#27B58A', '#24242D', '#3B82F6', '#EC4899', '#8B5CF6'];
        $avatarColor = $colors[array_rand($colors)];

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'student_id_number' => strtoupper($validated['student_id_number']),
            'batch_id' => $validated['batch_id'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'status' => 'pending',
            'avatar_color' => $avatarColor,
        ]);

        // Create welcome notification
        AppNotification::create([
            'user_id' => $user->id,
            'title' => 'Registration Submitted',
            'message' => 'Your student registration has been received and is currently under review by the university administrator for your batch.',
            'type' => 'system',
            'link' => '/pending-verification',
            'is_read' => false,
        ]);

        // Notify batch admin if one exists
        $batchAdmin = User::where('role', 'admin')->where('batch_id', $user->batch_id)->first();
        if ($batchAdmin) {
            AppNotification::create([
                'user_id' => $batchAdmin->id,
                'title' => 'New Student Verification Request',
                'message' => "Student {$user->name} ({$user->student_id_number}) registered for your batch and awaits verification.",
                'type' => 'system',
                'link' => route('admin.students.index', ['status' => 'pending']),
                'is_read' => false,
            ]);
        }

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function pendingNotice(): View
    {
        return view('auth.pending-verification');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Demo Fast-Switcher for Hackathon Judges
     */
    public function quickLogin(string $role): RedirectResponse
    {
        $user = match ($role) {
            'admin' => User::where('role', 'admin')->where('email', 'admin@unireplay.edu')->first(),
            'admin2' => User::where('role', 'admin')->where('email', 'admin2@unireplay.edu')->first(),
            'student' => User::where('role', 'student')->where('status', 'verified')->first(),
            'student2' => User::where('role', 'student')->where('status', 'verified')->skip(1)->first(),
            'pending' => User::where('role', 'student')->where('status', 'pending')->first(),
            default => null,
        };

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', "Logged in as {$user->name} (Administrator)");
            }

            if ($user->isPending()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('dashboard')->with('success', "Logged in as {$user->name} (Verified Student)");
        }

        return redirect()->route('login');
    }
}
