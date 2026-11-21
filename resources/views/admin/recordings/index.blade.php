@extends('layouts.admin')

@section('content')
<div class="space-y-6 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#1E283D]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                Lecture Recording Moderation
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Review submitted lecture video parts and attachments before they appear in the library.
            </p>
        </div>

        <!-- Batch Scope Toggle Pill: All Batches vs My Batch -->
        <div class="flex items-center gap-1 p-1 rounded-xl bg-[#0D1322] border border-[#1E283D] shadow-sm text-xs self-start sm:self-auto">
            <a href="{{ route('admin.recordings.index', ['status' => $status, 'scope' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $scope === 'all' ? 'bg-sky-500 text-black font-bold shadow-md shadow-sky-500/20' : 'text-slate-400 hover:text-white' }}">
                All University Batches
            </a>
            @if($admin->batch)
                <a href="{{ route('admin.recordings.index', ['status' => $status, 'scope' => 'my_batch']) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition {{ $scope === 'my_batch' ? 'bg-sky-500 text-black font-bold shadow-md shadow-sky-500/20' : 'text-slate-400 hover:text-white' }}">
                    {{ $admin->batch->batch_name }} Only
                </a>
            @endif
        </div>
    </div>

    <!-- Status Tabs Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-black font-bold shadow-md shadow-amber-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Pending Review</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-black text-amber-400 font-bold' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <a href="{{ route('admin.recordings.index', ['status' => 'approved', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'approved' ? 'bg-emerald-500 text-black font-bold shadow-md shadow-emerald-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Published</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'approved' ? 'bg-black text-emerald-400 font-bold' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' }}">
                    {{ $counts['approved'] }}
                </span>
            </a>

            <a href="{{ route('admin.recordings.index', ['status' => 'rejected', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'rejected' ? 'bg-rose-500 text-white font-bold shadow-md shadow-rose-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'rejected' ? 'bg-black text-rose-400 font-bold' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                    {{ $counts['rejected'] }}
                </span>
            </a>
        </div>
    </div>

    <!-- Recordings Grid -->
    @if($recordings->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recordings as $rec)
                <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] hover:border-sky-500/30 overflow-hidden flex flex-col justify-between group shadow-xl transition-all">
                    <div>
                        <!-- Thumbnail preview area -->
                        <div class="relative aspect-video bg-[#070913] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D1322] via-transparent to-transparent"></div>

                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                                    {{ $rec->session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/60 text-amber-400 border border-amber-500/20">
                                    Part {{ $rec->part_number }}
                                </span>
                            </div>

                            <a href="{{ route('admin.recordings.show', $rec) }}" class="w-12 h-12 rounded-full bg-sky-500 text-black flex items-center justify-center shadow-lg shadow-sky-500/30 group-hover:scale-110 transition z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5"></i>
                            </a>

                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-slate-300 z-10">
                                <span class="truncate max-w-[170px]">{{ $rec->session->batch->batch_name }}</span>
                                <span class="font-mono text-slate-400">{{ $rec->duration ?? '45m' }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold text-sky-400 uppercase tracking-wider">{{ $rec->session->subject->subject_name }}</span>
                                <span class="text-slate-400">Session #{{ $rec->session->lecture_number }}</span>
                            </div>

                            <h3 class="font-display font-bold text-sm text-white line-clamp-2 leading-snug group-hover:text-sky-300 transition">
                                {{ $rec->session->title }}
                            </h3>

                            <div class="text-[11px] text-slate-400 space-y-1 pt-1">
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-sky-400"></i>
                                    <span>Uploader: <strong class="text-slate-200">{{ $rec->uploader ? $rec->uploader->name : 'Unknown' }}</strong></span>
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Lecture: {{ $rec->session->lecture_date->format('M d, Y') }}</span>
                                </p>
                            </div>

                            @if($rec->status === 'rejected' && $rec->rejection_reason)
                                <div class="mt-2 p-2.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-[11px] text-rose-300">
                                    <strong class="text-rose-200">Rejection Reason:</strong> {{ $rec->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="px-5 py-3.5 border-t border-[#1E283D] bg-[#090D18] flex items-center justify-between" x-data="{ rejectModal: false }">
                        <a href="{{ route('admin.recordings.show', $rec) }}" class="text-xs font-semibold text-slate-300 hover:text-sky-400 flex items-center gap-1 transition">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Preview</span>
                        </a>

                        <div class="flex items-center gap-2">
                            @if($rec->status !== 'approved')
                                <form action="{{ route('admin.recordings.approve', $rec) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-bold transition shadow-md shadow-emerald-500/20 flex items-center gap-1">
                                        <i data-lucide="check" class="w-3 h-3 stroke-[3]"></i>
                                        <span>Publish</span>
                                    </button>
                                </form>
                            @endif

                            @if($rec->status !== 'rejected')
                                <button @click="rejectModal = true" 
                                        class="px-2.5 py-1.5 rounded-lg border border-[#1E283D] text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 text-xs transition">
                                    <span>Reject</span>
                                </button>

                                <!-- Rejection Reason Modal -->
                                <div x-show="rejectModal" 
                                     x-cloak 
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4">
                                    <div @click.away="rejectModal = false" class="bg-[#0D1322] border border-[#1E283D] rounded-2xl max-w-md w-full p-6 text-left space-y-4 shadow-2xl">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-display font-bold text-base text-white">Reject Recording Part</h3>
                                            <button @click="rejectModal = false" class="text-slate-400 hover:text-white">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </div>

                                        <p class="text-xs text-slate-400">
                                            State the reason for rejecting Part {{ $rec->part_number }} of '{{ $rec->session->title }}'. This explanation will be sent directly to the student uploader.
                                        </p>

                                        <form action="{{ route('admin.recordings.reject', $rec) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <textarea name="rejection_reason" 
                                                      rows="3" 
                                                      required 
                                                      class="w-full p-3 rounded-xl border border-[#1E283D] bg-[#070913] text-xs text-white focus:outline-none focus:ring-2 focus:ring-rose-500/40"
                                                      placeholder="e.g. Audio track is heavily distorted during minutes 12-25, or incorrect lecture number."></textarea>

                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="rejectModal = false" class="px-4 py-2 rounded-xl border border-[#1E283D] text-xs text-slate-400 hover:text-white">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-md">
                                                    Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $recordings->links() }}
        </div>
    @else
        <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] p-12 text-center space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center mx-auto">
                <i data-lucide="film" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-white">No recordings found</h3>
            <p class="text-xs text-slate-400">There are currently no lecture recordings under this filter.</p>
        </div>
    @endif

</div>
@endsection
