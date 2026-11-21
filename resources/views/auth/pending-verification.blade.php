@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="editorial-card p-8 sm:p-10 space-y-6 text-center">
        
        <!-- Status Icon -->
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200/60 flex items-center justify-center mx-auto shadow-sm">
            <i data-lucide="clock" class="w-8 h-8"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Account Status: Pending Verification</span>
            </span>
            <h1 class="font-display font-bold text-2xl sm:text-3xl text-[#17171D]">
                Registration Under Faculty Review
            </h1>
            <p class="text-sm text-[#777681] max-w-lg mx-auto leading-relaxed">
                Thank you for registering with UniReplay, <strong>{{ auth()->user()->name }}</strong>. 
                In order to protect university course recordings, every new student profile must be verified by the designated administrator for your academic batch.
            </p>
        </div>

        <!-- Student Application Card -->
        <div class="bg-[#FAF8F5] rounded-2xl p-5 border border-[#E5E2DC] text-left text-xs space-y-2 max-w-md mx-auto">
            <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                <span class="text-[#777681]">Student Name:</span>
                <span class="font-semibold text-[#17171D]">{{ auth()->user()->name }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                <span class="text-[#777681]">Email:</span>
                <span class="font-semibold text-[#17171D]">{{ auth()->user()->email }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                <span class="text-[#777681]">Student ID Number:</span>
                <span class="font-semibold text-[#17171D]">{{ auth()->user()->student_id_number ?? 'ST-PENDING' }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                <span class="text-[#777681]">Assigned Batch:</span>
                <span class="font-semibold text-[#7657F6]">{{ auth()->user()->batch ? auth()->user()->batch->batch_name : 'General' }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-[#777681]">Batch Moderator:</span>
                @php
                    $mod = \App\Models\User::where('role', 'admin')->where('batch_id', auth()->user()->batch_id)->first();
                @endphp
                <span class="font-semibold text-[#17171D]">{{ $mod ? $mod->name : 'Faculty Administrator' }}</span>
            </div>
        </div>

        <!-- Hackathon Interactive Demo Callout -->
        <div class="rounded-2xl bg-[#17171D] text-white p-6 text-left space-y-4 border border-[#24242D] shadow-lg">
            <div class="flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-[#B7A7FF]"></i>
                <h3 class="font-display font-semibold text-sm text-white">Hackathon Judges Demonstration:</h3>
            </div>
            <p class="text-xs text-gray-300 leading-relaxed">
                To test the approval workflow right now: switch to <strong>Admin (Dr. Sarah Chen)</strong> to approve this student, or switch to verified student <strong>Alex Rivera</strong> to test lecture streaming and uploads immediately.
            </p>
            <div class="flex flex-wrap gap-2 pt-1">
                <a href="{{ route('demo.quick-login', 'admin') }}" 
                   class="px-3.5 py-2 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    <span>Switch to Admin to Approve</span>
                </a>
                <a href="{{ route('demo.quick-login', 'student') }}" 
                   class="px-3.5 py-2 rounded-xl bg-[#24242D] hover:bg-[#2e2e3a] text-gray-200 text-xs font-medium border border-gray-700 transition flex items-center gap-1.5">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#27B58A]"></i>
                    <span>Switch to Verified Student</span>
                </a>
            </div>
        </div>

        <div class="pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-[#777681] hover:text-[#17171D] transition flex items-center justify-center gap-1.5 mx-auto">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Sign Out and Return Later</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
