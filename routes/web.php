<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RecordingApprovalController;
use App\Http\Controllers\Admin\StudentVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\LectureNoteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - UniReplay
|--------------------------------------------------------------------------
*/

// Public Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication & Demo Switcher
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Demo 1-Click Fast Switcher for Hackathon Judges
Route::get('/demo/login/{role}', [AuthController::class, 'quickLogin'])->name('demo.quick-login');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pending-verification', [AuthController::class, 'pendingNotice'])->name('verification.notice');

    // Notifications Center
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Protected Student Routes (Verified Students + Admins)
    Route::middleware('verified.student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Lecture Library & Viewing
        Route::get('/lectures', [LectureController::class, 'index'])->name('lectures.index');
        Route::get('/lectures/{session}', [LectureController::class, 'show'])->name('lectures.show');
        Route::get('/recordings/{recording}/stream', [LectureController::class, 'stream'])->name('recordings.stream');

        // Recording Uploads
        Route::get('/upload', [UploadController::class, 'create'])->name('upload.create');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
        Route::get('/my-uploads', [UploadController::class, 'myUploads'])->name('my-uploads');

        // Bookmarks
        Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
        Route::post('/bookmarks/{session}/toggle', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

        // AI Smart Lecture Notes
        Route::get('/my-notes', [LectureNoteController::class, 'index'])->name('notes.index');
        Route::post('/lectures/{session}/notes/generate', [LectureNoteController::class, 'generate'])->name('lectures.notes.generate');
        Route::put('/notes/{note}', [LectureNoteController::class, 'update'])->name('notes.update');
        Route::get('/notes/{note}/download', [LectureNoteController::class, 'download'])->name('notes.download');
    });

    // Protected Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Student Verification
        Route::get('/students', [StudentVerificationController::class, 'index'])->name('students.index');
        Route::post('/students/{user}/approve', [StudentVerificationController::class, 'approve'])->name('students.approve');
        Route::post('/students/{user}/reject', [StudentVerificationController::class, 'reject'])->name('students.reject');

        // Recording Moderation & Approvals
        Route::get('/recordings', [RecordingApprovalController::class, 'index'])->name('recordings.index');
        Route::get('/recordings/{recording}', [RecordingApprovalController::class, 'show'])->name('recordings.show');
        Route::post('/recordings/{recording}/approve', [RecordingApprovalController::class, 'approve'])->name('recordings.approve');
        Route::post('/recordings/{recording}/reject', [RecordingApprovalController::class, 'reject'])->name('recordings.reject');
    });
});
