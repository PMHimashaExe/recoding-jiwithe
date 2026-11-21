@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#1E283D]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                My Upload History
            </h1>
            <p class="text-xs sm:text-sm text-[#94A3B8] mt-1">
                Track your submitted lecture recordings and administrator review decisions.
            </p>
        </div>

        <a href="{{ route('upload.create') }}" 
           class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-[0_0_15px_rgba(245,158,11,0.3)] flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Upload New Recording</span>
        </a>
    </div>

    <!-- Stat Metric Tabs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('my-uploads') }}" 
           class="editorial-card p-4 transition {{ !$status ? 'ring-2 ring-cyan-400 border-transparent shadow-[0_0_15px_rgba(56,189,248,0.2)]' : '' }}">
            <span class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-wider">All Uploads</span>
            <p class="font-display font-bold text-2xl text-white mt-1">{{ $stats['total'] }}</p>
        </a>

        <a href="{{ route('my-uploads', ['status' => 'pending']) }}" 
           class="editorial-card p-4 transition {{ $status === 'pending' ? 'ring-2 ring-amber-400 border-transparent shadow-[0_0_15px_rgba(245,158,11,0.2)]' : '' }}">
            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">In Review</span>
            <p class="font-display font-bold text-2xl text-amber-400 mt-1">{{ $stats['pending'] }}</p>
        </a>

        <a href="{{ route('my-uploads', ['status' => 'approved']) }}" 
           class="editorial-card p-4 transition {{ $status === 'approved' ? 'ring-2 ring-emerald-400 border-transparent shadow-[0_0_15px_rgba(16,185,129,0.2)]' : '' }}">
            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Approved</span>
            <p class="font-display font-bold text-2xl text-emerald-400 mt-1">{{ $stats['approved'] }}</p>
        </a>

        <a href="{{ route('my-uploads', ['status' => 'rejected']) }}" 
           class="editorial-card p-4 transition {{ $status === 'rejected' ? 'ring-2 ring-rose-500 border-transparent shadow-[0_0_15px_rgba(244,63,94,0.2)]' : '' }}">
            <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Rejected</span>
            <p class="font-display font-bold text-2xl text-rose-400 mt-1">{{ $stats['rejected'] }}</p>
        </a>
    </div>

    <!-- Uploads List Table / Cards -->
    @if($uploads->isNotEmpty())
        <div class="editorial-card overflow-hidden">
            <div class="divide-y divide-[#1E283D]">
                @foreach($uploads as $upload)
                    <div class="p-5 sm:p-6 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 font-mono">
                                    {{ $upload->session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#131D31] text-white border border-[#1E283D]">
                                    Session #{{ $upload->session->lecture_number }} · Part {{ $upload->part_number }}
                                </span>
                                <span class="text-xs text-[#94A3B8]">
                                    Batch: {{ $upload->session->batch->batch_name }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if($upload->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span>Approved & Published</span>
                                    </span>
                                @elseif($upload->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        <span>Pending Admin Review</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                        <span>Rejected</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Title and info -->
                        <h3 class="font-display font-bold text-base text-white">
                            {{ $upload->session->title }}
                        </h3>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-[#94A3B8]">
                            <span>Delivered on: <strong class="text-white">{{ $upload->session->lecture_date->format('M d, Y') }}</strong></span>
                            <span>·</span>
                            <span>Submitted: <strong class="text-white">{{ $upload->created_at->format('M d, Y @ h:i A') }}</strong></span>
                            @if($upload->drive_file_id)
                                <span>·</span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2 py-0.5 rounded-md">
                                    <i data-lucide="hard-drive" class="w-3 h-3"></i> Google Drive
                                </span>
                            @endif
                            @if($upload->duration)
                                <span>·</span>
                                <span>Duration: <strong class="text-white">{{ $upload->duration }}</strong></span>
                            @endif
                            @if($upload->reviewer)
                                <span>·</span>
                                <span>Reviewed by: <strong class="text-amber-400">{{ $upload->reviewer->name }}</strong></span>
                            @endif
                        </div>

                        <!-- Rejection Reason Alert Box -->
                        @if($upload->status === 'rejected' && $upload->rejection_reason)
                            <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-xs text-rose-300 flex items-start gap-2.5">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="font-bold text-rose-200">Faculty Moderator Note:</strong>
                                    <p class="mt-0.5 leading-relaxed">{{ $upload->rejection_reason }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Approved Live Link -->
                        @if($upload->status === 'approved')
                            <div class="pt-2 flex items-center justify-end">
                                <a href="{{ route('lectures.show', ['session' => $upload->session_id, 'part' => $upload->id]) }}" 
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-amber-400 hover:text-amber-300">
                                    <span>Watch in Library</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-[#1E283D]">
                {{ $uploads->links() }}
            </div>
        </div>
    @else
        <div class="editorial-card p-12 text-center space-y-4 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center mx-auto">
                <i data-lucide="upload" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-white">No uploads found</h3>
            <p class="text-xs text-[#94A3B8]">You don't have any uploads in this status category.</p>
            <div class="pt-2">
                <a href="{{ route('upload.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Upload a Lecture</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
