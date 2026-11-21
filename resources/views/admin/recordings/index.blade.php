@extends('layouts.admin')

@section('content')
<div class="space-y-6 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E5E2DC]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[#17171D] tracking-tight">
                Lecture Recording Moderation
            </h1>
            <p class="text-xs sm:text-sm text-[#777681] mt-1">
                Review submitted lecture video parts and attachments before they appear in the library.
            </p>
        </div>

        <!-- Batch Scope Toggle Pill: My Batch vs All Batches -->
        <div class="flex items-center gap-1 p-1 rounded-xl bg-white border border-[#E5E2DC] shadow-sm text-xs self-start sm:self-auto">
            <a href="{{ route('admin.recordings.index', ['status' => $status, 'scope' => 'my_batch']) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $scope === 'my_batch' ? 'bg-[#17171D] text-white shadow-sm' : 'text-[#777681] hover:text-[#17171D]' }}">
                {{ $admin->batch ? $admin->batch->batch_name : 'My Batch' }}
            </a>
            <a href="{{ route('admin.recordings.index', ['status' => $status, 'scope' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $scope === 'all' ? 'bg-[#17171D] text-white shadow-sm' : 'text-[#777681] hover:text-[#17171D]' }}">
                All University Batches
            </a>
        </div>
    </div>

    <!-- Status Tabs Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.recordings.index', ['status' => 'pending', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-[#7657F6] text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Pending Review</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-white text-[#7657F6] font-bold' : 'bg-purple-100 text-[#7657F6]' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <a href="{{ route('admin.recordings.index', ['status' => 'approved', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'approved' ? 'bg-[#27B58A] text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Published</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'approved' ? 'bg-white text-[#27B58A] font-bold' : 'bg-[#EBF8F4] text-[#27B58A]' }}">
                    {{ $counts['approved'] }}
                </span>
            </a>

            <a href="{{ route('admin.recordings.index', ['status' => 'rejected', 'scope' => $scope]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'rejected' ? 'bg-rose-500 text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'rejected' ? 'bg-white text-rose-700 font-bold' : 'bg-rose-100 text-rose-800' }}">
                    {{ $counts['rejected'] }}
                </span>
            </a>
        </div>
    </div>

    <!-- Recordings Grid -->
    @if($recordings->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recordings as $rec)
                <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail preview area -->
                        <div class="relative aspect-video bg-[#17171D] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-[#17171D]">
                                    {{ $rec->session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/60 text-white border border-white/10">
                                    Part {{ $rec->part_number }}
                                </span>
                            </div>

                            <a href="{{ route('admin.recordings.show', $rec) }}" class="w-12 h-12 rounded-full bg-white/90 text-[#17171D] flex items-center justify-center shadow-lg group-hover:scale-110 transition z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5"></i>
                            </a>

                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200 z-10">
                                <span class="truncate max-w-[170px]">{{ $rec->session->batch->batch_name }}</span>
                                <span>{{ $rec->duration ?? '45m' }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold text-[#7657F6] uppercase tracking-wider">{{ $rec->session->subject->subject_name }}</span>
                                <span>Session #{{ $rec->session->lecture_number }}</span>
                            </div>

                            <h3 class="font-display font-bold text-sm text-[#17171D] line-clamp-2 leading-snug">
                                {{ $rec->session->title }}
                            </h3>

                            <div class="text-[11px] text-[#777681] space-y-1 pt-1">
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-[#7657F6]"></i>
                                    <span>Uploader: <strong>{{ $rec->uploader ? $rec->uploader->name : 'Unknown' }}</strong></span>
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    <span>Lecture: {{ $rec->session->lecture_date->format('M d, Y') }}</span>
                                </p>
                            </div>

                            @if($rec->status === 'rejected' && $rec->rejection_reason)
                                <div class="mt-2 p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-[11px] text-rose-800">
                                    <strong>Rejection Reason:</strong> {{ $rec->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="px-5 py-3.5 border-t border-[#E5E2DC] bg-[#FAF8F5] flex items-center justify-between" x-data="{ rejectModal: false }">
                        <a href="{{ route('admin.recordings.show', $rec) }}" class="text-xs font-semibold text-[#17171D] hover:text-[#7657F6] flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Preview</span>
                        </a>

                        <div class="flex items-center gap-2">
                            @if($rec->status !== 'approved')
                                <form action="{{ route('admin.recordings.approve', $rec) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition shadow-sm flex items-center gap-1">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>Publish</span>
                                    </button>
                                </form>
                            @endif

                            @if($rec->status !== 'rejected')
                                <button @click="rejectModal = true" 
                                        class="px-2.5 py-1.5 rounded-lg border border-[#E5E2DC] text-[#777681] hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 text-xs transition">
                                    <span>Reject</span>
                                </button>

                                <!-- Rejection Reason Modal -->
                                <div x-show="rejectModal" 
                                     x-cloak 
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                                    <div @click.away="rejectModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 text-left space-y-4 shadow-2xl">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-display font-bold text-base text-[#17171D]">Reject Recording Part</h3>
                                            <button @click="rejectModal = false" class="text-gray-400 hover:text-gray-600">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </div>

                                        <p class="text-xs text-[#777681]">
                                            State the reason for rejecting Part {{ $rec->part_number }} of '{{ $rec->session->title }}'. This explanation will be sent directly to the student uploader.
                                        </p>

                                        <form action="{{ route('admin.recordings.reject', $rec) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <textarea name="rejection_reason" 
                                                      rows="3" 
                                                      required 
                                                      class="w-full p-3 rounded-xl border border-[#E5E2DC] text-xs text-[#17171D] focus:outline-none focus:ring-2 focus:ring-rose-500/30"
                                                      placeholder="e.g. Audio track is heavily distorted during minutes 12-25, or incorrect lecture number."></textarea>

                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="rejectModal = false" class="px-4 py-2 rounded-xl border border-[#E5E2DC] text-xs text-[#777681]">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-sm">
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
        <div class="editorial-card p-12 text-center space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center mx-auto">
                <i data-lucide="film" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-[#17171D]">No recordings found</h3>
            <p class="text-xs text-[#777681]">There are currently no lecture recordings under this filter.</p>
        </div>
    @endif

</div>
@endsection
