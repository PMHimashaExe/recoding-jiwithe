@extends('layouts.admin')

@section('content')
<div class="space-y-8 py-2">

    <!-- Moderator Assignment Banner -->
    <div class="editorial-card p-6 bg-gradient-to-r from-[#0D1322] via-[#0F182A] to-[#141A2E] border border-[#1E283D] flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.35)] shrink-0">
                <i data-lucide="shield" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-display font-extrabold text-xl text-white">
                        Faculty Moderation Cockpit
                    </h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 uppercase">
                        Active Moderator
                    </span>
                </div>
                <p class="text-xs text-[#94A3B8] mt-0.5">
                    Welcome, <strong class="text-white">{{ $admin->name }}</strong> · Assigned to: 
                    <strong class="text-cyan-400 font-mono">{{ $adminBatch ? $adminBatch->batch_name : 'All Batches' }}</strong>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 relative z-10">
            <a href="{{ route('admin.students.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-[#131D31] hover:bg-[#1A2640] border border-cyan-500/30 text-xs font-semibold text-cyan-300 transition shadow-sm flex items-center gap-1.5">
                <i data-lucide="user-check" class="w-4 h-4 text-cyan-400"></i>
                <span>Review Students</span>
            </a>
            <a href="{{ route('admin.recordings.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-[0_0_15px_rgba(245,158,11,0.3)] flex items-center gap-1.5">
                <i data-lucide="film" class="w-4 h-4"></i>
                <span>Review Recordings</span>
            </a>
        </div>
    </div>

    <!-- 4 High-Level Key Performance Indicators -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Pending Students -->
        <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="editorial-card p-5 group hover:border-amber-400 transition">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-400">Pending Students</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-amber-400">{{ $pendingStudentsCount }}</span>
                <span class="text-[11px] text-[#94A3B8] group-hover:underline">Awaiting verification →</span>
            </div>
        </a>

        <!-- Pending Recordings in Admin's Batch -->
        <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => 'my_batch']) }}" class="editorial-card p-5 group hover:border-cyan-400 transition">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider text-cyan-400">Pending in My Batch</span>
                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-cyan-400">{{ $pendingRecordingsBatchCount }}</span>
                <span class="text-[11px] text-[#94A3B8] group-hover:underline">{{ $adminBatch ? $adminBatch->batch_name : 'My Batch' }} →</span>
            </div>
        </a>

        <!-- Total Verified Students -->
        <a href="{{ route('admin.students.index', ['status' => 'verified']) }}" class="editorial-card p-5 group hover:border-emerald-400 transition">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Verified Students</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-white">{{ $totalVerifiedStudents }}</span>
                <span class="text-[11px] text-[#94A3B8] group-hover:underline">Full library access →</span>
            </div>
        </a>

        <!-- Published Recordings -->
        <a href="{{ route('admin.recordings.index', ['status' => 'approved']) }}" class="editorial-card p-5 group hover:border-amber-400 transition">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-400">Published Videos</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-white">{{ $totalPublishedRecordings }}</span>
                <span class="text-[11px] text-[#94A3B8] group-hover:underline">Active library parts →</span>
            </div>
        </a>

    </div>

    <!-- 2 Priority Queues: Pending Student Verifications & Pending Lecture Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Queue 1: Pending Student Registrations -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_6px_#F59E0B]"></span>
                    <h2 class="font-display font-bold text-lg text-white">Student Verification Queue</h2>
                    @if($pendingStudentsCount > 0)
                        <span class="px-2 py-0.2 rounded-full text-xs font-bold bg-amber-400 text-black">
                            {{ $pendingStudentsCount }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300">
                    View All
                </a>
            </div>

            <div class="editorial-card divide-y divide-[#1E283D]">
                @forelse($pendingStudents as $student)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs text-white shrink-0"
                                 style="background-color: {{ $student->avatar_color ?? '#D97706' }}">
                                {{ $student->initials }}
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-xs text-white">{{ $student->name }}</h3>
                                <p class="text-[11px] text-[#94A3B8] truncate">{{ $student->email }}</p>
                                <div class="flex items-center gap-2 text-[10px] text-[#94A3B8] mt-0.5">
                                    <span class="font-medium text-cyan-400">{{ $student->batch ? $student->batch->batch_name : 'No batch' }}</span>
                                    <span>·</span>
                                    <span class="font-mono">ID: {{ $student->student_id_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 1-Click Verification Actions -->
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                        title="Approve Student Account">
                                    <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                                    <span>Approve</span>
                                </button>
                            </form>

                            <form action="{{ route('admin.students.reject', $student) }}" method="POST" onsubmit="return confirm('Decline this student registration request?');">
                                @csrf
                                <button type="submit" 
                                        class="px-2.5 py-1.5 rounded-lg border border-[#1E283D] hover:bg-rose-500/15 hover:text-rose-400 text-[#94A3B8] text-xs transition"
                                        title="Reject Student">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#94A3B8] space-y-2">
                        <i data-lucide="check-check" class="w-8 h-8 text-emerald-400 mx-auto"></i>
                        <p class="font-medium text-white">Zero Pending Registrations</p>
                        <p>All student account requests have been verified.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Queue 2: Pending Recording Approvals -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 shadow-[0_0_6px_#38BDF8]"></span>
                    <h2 class="font-display font-bold text-lg text-white">Recording Moderation Queue</h2>
                    @if($totalPendingRecordings > 0)
                        <span class="px-2 py-0.2 rounded-full text-xs font-bold bg-cyan-400 text-black">
                            {{ $totalPendingRecordings }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => 'all']) }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300">
                    View All
                </a>
            </div>

            <div class="editorial-card divide-y divide-[#1E283D]">
                @forelse($pendingRecordings as $rec)
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 font-mono">
                                        {{ $rec->session->subject->subject_code }}
                                    </span>
                                    <span class="text-xs font-bold text-white">
                                        Session #{{ $rec->session->lecture_number }} · Part {{ $rec->part_number }}
                                    </span>
                                </div>
                                <h3 class="font-display font-semibold text-xs text-white mt-1 line-clamp-1">
                                    {{ $rec->session->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-[10px] text-[#94A3B8] mt-0.5">
                                    <span>By: {{ $rec->uploader ? $rec->uploader->name : 'Student' }}</span>
                                    <span>·</span>
                                    <span>{{ $rec->session->batch->batch_name }}</span>
                                    <span>·</span>
                                    <span>Date: {{ $rec->session->lecture_date->format('M d') }}</span>
                                </div>
                            </div>

                            <!-- Review & Approve actions -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('admin.recordings.show', $rec) }}" 
                                   class="px-2.5 py-1.5 rounded-lg border border-[#1E283D] hover:bg-[#131D31] text-xs font-medium text-cyan-300 transition"
                                   title="Inspect Recording">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>

                                <form action="{{ route('admin.recordings.approve', $rec) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                                        <span>Publish</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#94A3B8] space-y-2">
                        <i data-lucide="film" class="w-8 h-8 text-cyan-400 mx-auto"></i>
                        <p class="font-medium text-white">No Pending Uploads</p>
                        <p>All submitted lecture recordings for this batch have been reviewed.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Moderation Activity Log -->
    <div class="space-y-4">
        <h2 class="font-display font-bold text-lg text-white">Recent Moderation Decisions</h2>
        <div class="editorial-card divide-y divide-[#1E283D]">
            @foreach($recentApprovals as $item)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                            {{ $item->status === 'approved' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }}">
                            <i data-lucide="{{ $item->status === 'approved' ? 'check' : 'x' }}" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">
                                <span class="text-cyan-400 font-mono">{{ $item->session->subject->subject_code }}</span> — {{ $item->session->title }} (Part {{ $item->part_number }})
                            </p>
                            <p class="text-[11px] text-[#94A3B8]">
                                Uploader: {{ $item->uploader ? $item->uploader->name : 'Student' }} · Reviewed by {{ $item->reviewer ? $item->reviewer->name : 'Admin' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        <span class="text-[10px] text-[#94A3B8]">{{ $item->updated_at->diffForHumans() }}</span>
                        @if($item->status === 'approved')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">Published</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">Rejected</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
