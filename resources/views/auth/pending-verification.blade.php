@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="editorial-card p-8 sm:p-10 space-y-6 text-center border-amber-500/30 shadow-[0_0_30px_rgba(245,158,11,0.15)]">
        
        <!-- Status Icon -->
        <div class="w-16 h-16 rounded-2xl bg-amber-500/15 text-amber-400 border border-amber-500/30 flex items-center justify-center mx-auto shadow-[0_0_20px_rgba(245,158,11,0.3)]">
            <i data-lucide="clock" class="w-8 h-8"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/15 text-amber-300 border border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_#F59E0B] animate-pulse"></span>
                <span>Account Status: Pending Verification</span>
            </span>
            <h1 class="font-display font-bold text-2xl sm:text-3xl text-white">
                Registration Under Faculty Review
            </h1>
            <p class="text-sm text-[#94A3B8] max-w-lg mx-auto leading-relaxed">
                Thank you for registering with UniReplay, <strong class="text-white">{{ auth()->user()->name }}</strong>. 
                In order to protect university course recordings, every new student profile must be verified by the designated administrator for your academic batch.
            </p>
        </div>

        <!-- Student Application Card -->
        <div class="bg-[#0A0E1A] rounded-2xl p-5 border border-[#1E283D] text-left text-xs space-y-2 max-w-md mx-auto">
            <div class="flex justify-between py-1 border-b border-[#1E283D]">
                <span class="text-[#94A3B8]">Student Name:</span>
                <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#1E283D]">
                <span class="text-[#94A3B8]">Email:</span>
                <span class="font-semibold text-white">{{ auth()->user()->email }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#1E283D]">
                <span class="text-[#94A3B8]">Student ID Number:</span>
                <span class="font-semibold text-white font-mono">{{ auth()->user()->student_id_number ?? 'ST-PENDING' }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-[#1E283D]">
                <span class="text-[#94A3B8]">Assigned Batch:</span>
                <span class="font-semibold text-cyan-400">{{ auth()->user()->batch ? auth()->user()->batch->batch_name : 'General' }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-[#94A3B8]">Batch Moderator:</span>
                @php
                    $mod = \App\Models\User::where('role', 'admin')->where('batch_id', auth()->user()->batch_id)->first();
                @endphp
                <span class="font-semibold text-amber-400">{{ $mod ? $mod->name : 'Faculty Administrator' }}</span>
            </div>
        </div>

        <!-- Hackathon Interactive Demo Callout -->
        <div class="rounded-2xl bg-[#0F172A] text-white p-6 text-left space-y-4 border border-cyan-500/30 shadow-[0_0_20px_rgba(56,189,248,0.15)]">
            <div class="flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-amber-400"></i>
                <h3 class="font-display font-semibold text-sm text-white">Hackathon Judges Demonstration:</h3>
            </div>
            <p class="text-xs text-[#94A3B8] leading-relaxed">
                To test the approval workflow right now: switch to <strong class="text-white">Admin (Dr. Sarah Chen)</strong> to approve this student, or switch to verified student <strong class="text-white">Alex Rivera</strong> to test lecture streaming and uploads immediately.
            </p>
            <div class="flex flex-wrap gap-2 pt-1">
                <a href="{{ route('demo.quick-login', 'admin') }}" 
                   class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-[0_0_12px_rgba(245,158,11,0.3)]">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    <span>Switch to Admin to Approve</span>
                </a>
                <a href="{{ route('demo.quick-login', 'student') }}" 
                   class="px-3.5 py-2 rounded-xl bg-[#090D18] hover:bg-[#131D31] text-cyan-300 text-xs font-medium border border-cyan-500/40 transition flex items-center gap-1.5">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-cyan-400"></i>
                    <span>Switch to Verified Student</span>
                </a>
            </div>
        </div>

        <div class="pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-[#94A3B8] hover:text-white transition flex items-center justify-center gap-1.5 mx-auto">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Sign Out and Return Later</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
