@extends('layouts.admin')

@section('content')
<div class="space-y-8 py-2">

    <!-- Moderator Assignment Banner -->
    <div class="editorial-card p-6 bg-gradient-to-r from-white via-white to-[#F0EDFF]/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#17171D] text-white flex items-center justify-center shadow-md shrink-0">
                <i data-lucide="shield" class="w-6 h-6 text-[#B7A7FF]"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-display font-extrabold text-xl text-[#17171D]">
                        Faculty Moderation Cockpit
                    </h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EBF8F4] text-[#27B58A] border border-[#27B58A]/30 uppercase">
                        Active Moderator
                    </span>
                </div>
                <p class="text-xs text-[#777681] mt-0.5">
                    Welcome, <strong>{{ $admin->name }}</strong> · Assigned to: 
                    <strong class="text-[#7657F6]">{{ $adminBatch ? $adminBatch->batch_name : 'All Batches' }}</strong>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#E5E2DC] text-xs font-semibold text-[#17171D] transition shadow-sm flex items-center gap-1.5">
                <i data-lucide="user-check" class="w-4 h-4 text-[#7657F6]"></i>
                <span>Review Students</span>
            </a>
            <a href="{{ route('admin.recordings.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition shadow-sm hover:shadow flex items-center gap-1.5">
                <i data-lucide="film" class="w-4 h-4"></i>
                <span>Review Recordings</span>
            </a>
        </div>
    </div>

    <!-- 4 High-Level Key Performance Indicators -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Pending Students -->
        <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="editorial-card p-5 group hover:border-amber-400 transition">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pending Students</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-amber-600">{{ $pendingStudentsCount }}</span>
                <span class="text-[11px] text-[#777681] group-hover:underline">Awaiting verification →</span>
            </div>
        </a>

        <!-- Pending Recordings in Admin's Batch -->
        <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => 'my_batch']) }}" class="editorial-card p-5 group hover:border-[#7657F6] transition">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#7657F6]">Pending in My Batch</span>
                <div class="w-8 h-8 rounded-lg bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-[#7657F6]">{{ $pendingRecordingsBatchCount }}</span>
                <span class="text-[11px] text-[#777681] group-hover:underline">{{ $adminBatch ? $adminBatch->batch_name : 'My Batch' }} →</span>
            </div>
        </a>

        <!-- Total Verified Students -->
        <a href="{{ route('admin.students.index', ['status' => 'verified']) }}" class="editorial-card p-5 group hover:border-[#27B58A] transition">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#27B58A]">Verified Students</span>
                <div class="w-8 h-8 rounded-lg bg-[#EBF8F4] text-[#27B58A] flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-[#17171D]">{{ $totalVerifiedStudents }}</span>
                <span class="text-[11px] text-[#777681] group-hover:underline">Full library access →</span>
            </div>
        </a>

        <!-- Published Recordings -->
        <a href="{{ route('admin.recordings.index', ['status' => 'approved']) }}" class="editorial-card p-5 group hover:border-[#17171D] transition">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#17171D]">Published Videos</span>
                <div class="w-8 h-8 rounded-lg bg-gray-100 text-[#17171D] flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-display font-bold text-3xl text-[#17171D]">{{ $totalPublishedRecordings }}</span>
                <span class="text-[11px] text-[#777681] group-hover:underline">Active library parts →</span>
            </div>
        </a>

    </div>

    <!-- 2 Priority Queues: Pending Student Verifications & Pending Lecture Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Queue 1: Pending Student Registrations -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h2 class="font-display font-bold text-lg text-[#17171D]">Student Verification Queue</h2>
                    @if($pendingStudentsCount > 0)
                        <span class="px-2 py-0.2 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            {{ $pendingStudentsCount }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-[#7657F6] hover:underline">
                    View All
                </a>
            </div>

            <div class="editorial-card divide-y divide-[#E5E2DC]">
                @forelse($pendingStudents as $student)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs text-white shrink-0"
                                 style="background-color: {{ $student->avatar_color ?? '#7657F6' }}">
                                {{ $student->initials }}
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-xs text-[#17171D]">{{ $student->name }}</h3>
                                <p class="text-[11px] text-[#777681] truncate">{{ $student->email }}</p>
                                <div class="flex items-center gap-2 text-[10px] text-[#777681] mt-0.5">
                                    <span class="font-medium text-[#7657F6]">{{ $student->batch ? $student->batch->batch_name : 'No batch' }}</span>
                                    <span>·</span>
                                    <span>ID: {{ $student->student_id_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 1-Click Verification Actions -->
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.students.approve', $student) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1.5 rounded-lg bg-[#27B58A] hover:bg-[#209c76] text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm"
                                        title="Approve Student Account">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Approve</span>
                                </button>
                            </form>

                            <form action="{{ route('admin.students.reject', $student) }}" method="POST" onsubmit="return confirm('Decline this student registration request?');">
                                @csrf
                                <button type="submit" 
                                        class="px-2.5 py-1.5 rounded-lg border border-[#E5E2DC] hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-[#777681] text-xs transition"
                                        title="Reject Student">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#777681] space-y-2">
                        <i data-lucide="check-check" class="w-8 h-8 text-[#27B58A] mx-auto"></i>
                        <p class="font-medium text-[#17171D]">Zero Pending Registrations</p>
                        <p>All student account requests have been verified.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Queue 2: Pending Recording Approvals for Admin Batch -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#7657F6]"></span>
                    <h2 class="font-display font-bold text-lg text-[#17171D]">Recording Moderation Queue</h2>
                    @if($pendingRecordingsBatchCount > 0)
                        <span class="px-2 py-0.2 rounded-full text-xs font-bold bg-purple-100 text-[#7657F6]">
                            {{ $pendingRecordingsBatchCount }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => 'my_batch']) }}" class="text-xs font-semibold text-[#7657F6] hover:underline">
                    View All
                </a>
            </div>

            <div class="editorial-card divide-y divide-[#E5E2DC]">
                @forelse($pendingRecordings as $rec)
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#F0EDFF] text-[#7657F6]">
                                        {{ $rec->session->subject->subject_code }}
                                    </span>
                                    <span class="text-xs font-bold text-[#17171D]">
                                        Session #{{ $rec->session->lecture_number }} · Part {{ $rec->part_number }}
                                    </span>
                                </div>
                                <h3 class="font-display font-semibold text-xs text-[#17171D] mt-1 line-clamp-1">
                                    {{ $rec->session->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-[10px] text-[#777681] mt-0.5">
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
                                   class="px-2.5 py-1.5 rounded-lg border border-[#E5E2DC] hover:bg-[#FAF8F5] text-xs font-medium text-[#17171D] transition"
                                   title="Inspect Recording">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>

                                <form action="{{ route('admin.recordings.approve', $rec) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span>Publish</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#777681] space-y-2">
                        <i data-lucide="film" class="w-8 h-8 text-[#7657F6] mx-auto"></i>
                        <p class="font-medium text-[#17171D]">No Pending Uploads</p>
                        <p>All submitted lecture recordings for this batch have been reviewed.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Moderation Activity Log -->
    <div class="space-y-4">
        <h2 class="font-display font-bold text-lg text-[#17171D]">Recent Moderation Decisions</h2>
        <div class="editorial-card divide-y divide-[#E5E2DC]">
            @foreach($recentApprovals as $item)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                            {{ $item->status === 'approved' ? 'bg-[#EBF8F4] text-[#27B58A]' : 'bg-rose-50 text-rose-600' }}">
                            <i data-lucide="{{ $item->status === 'approved' ? 'check' : 'x' }}" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-[#17171D]">
                                {{ $item->session->subject->subject_code }} — {{ $item->session->title }} (Part {{ $item->part_number }})
                            </p>
                            <p class="text-[11px] text-[#777681]">
                                Uploader: {{ $item->uploader ? $item->uploader->name : 'Student' }} · Reviewed by {{ $item->reviewer ? $item->reviewer->name : 'Admin' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        <span class="text-[10px] text-[#777681]">{{ $item->updated_at->diffForHumans() }}</span>
                        @if($item->status === 'approved')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EBF8F4] text-[#27B58A]">Published</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600">Rejected</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
