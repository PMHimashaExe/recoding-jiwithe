@extends('layouts.admin')

@section('content')
<div class="space-y-6 py-2">

    <!-- Top Action & Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.recordings.index', ['status' => $recording->status]) }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#777681] hover:text-[#17171D] transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Recordings List</span>
        </a>

        <!-- Status Indicator Pill -->
        <div>
            @if($recording->status === 'approved')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#EBF8F4] text-[#27B58A] border border-[#27B58A]/30">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    <span>Approved & Published</span>
                </span>
            @elseif($recording->status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>Pending Moderation</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    <span>Rejected</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Video Inspection Player Theater -->
    <div class="rounded-3xl bg-[#17171D] text-white overflow-hidden border border-[#24242D] shadow-2xl p-6 sm:p-8 space-y-6">
        <div class="relative aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-inner flex items-center justify-center">
            @if($recording->drive_file_id)
                <iframe src="https://drive.google.com/file/d/{{ $recording->drive_file_id }}/preview" 
                        class="w-full h-full border-0" 
                        allow="autoplay; encrypted-media" 
                        allowfullscreen>
                </iframe>
            @else
                <video controls preload="metadata" class="w-full h-full object-contain">
                    <source src="{{ route('recordings.stream', $recording) }}" type="video/mp4">
                    Your browser does not support HTML5 video playback.
                </video>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-white/10">
            <div>
                <span class="text-xs text-[#B7A7FF] font-semibold uppercase tracking-wider">
                    {{ $recording->session->subject->subject_code }} · Session #{{ $recording->session->lecture_number }}
                </span>
                <h1 class="font-display font-bold text-xl text-white mt-0.5">
                    {{ $recording->session->title }} (Part {{ $recording->part_number }})
                </h1>
            </div>

            <!-- Approval / Rejection Controls Bar -->
            <div class="flex items-center gap-3" x-data="{ rejectOpen: false }">
                @if($recording->status !== 'approved')
                    <form action="{{ route('admin.recordings.approve', $recording) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#27B58A] hover:bg-[#209c76] text-white text-xs font-semibold transition shadow-md flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Approve & Publish to Library</span>
                        </button>
                    </form>
                @endif

                @if($recording->status !== 'rejected')
                    <button @click="rejectOpen = true" 
                            class="px-4 py-2.5 rounded-xl bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/30 text-xs font-semibold transition flex items-center gap-1.5">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span>Reject Submission</span>
                    </button>

                    <!-- Reject Modal -->
                    <div x-show="rejectOpen" 
                         x-cloak 
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                        <div @click.away="rejectOpen = false" class="bg-white text-[#17171D] rounded-2xl max-w-md w-full p-6 text-left space-y-4 shadow-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display font-bold text-base text-[#17171D]">Reject Lecture Recording</h3>
                                <button @click="rejectOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <p class="text-xs text-[#777681]">
                                State the rejection reason. This message will be sent to the uploader <strong>{{ $recording->uploader ? $recording->uploader->name : 'Student' }}</strong>.
                            </p>
                            <form action="{{ route('admin.recordings.reject', $recording) }}" method="POST" class="space-y-4">
                                @csrf
                                <textarea name="rejection_reason" 
                                          rows="3" 
                                          required 
                                          class="w-full p-3 rounded-xl border border-[#E5E2DC] text-xs text-[#17171D] focus:outline-none focus:ring-2 focus:ring-rose-500/30"
                                          placeholder="e.g. Video resolution is unreadable or wrong lecture session."></textarea>

                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="rejectOpen = false" class="px-4 py-2 rounded-xl border border-[#E5E2DC] text-xs text-[#777681]">
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

    <!-- Metadata & Resources Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Metadata -->
        <div class="editorial-card p-6 space-y-4">
            <h3 class="font-display font-bold text-sm text-[#17171D]">Recording Details</h3>
            
            <div class="space-y-2 text-xs text-[#777681]">
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Subject:</span>
                    <span class="font-semibold text-[#17171D]">{{ $recording->session->subject->subject_name }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Batch:</span>
                    <span class="font-semibold text-[#17171D]">{{ $recording->session->batch->batch_name }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Lecture Date:</span>
                    <span class="font-semibold text-[#17171D]">{{ $recording->session->lecture_date->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Session Number:</span>
                    <span class="font-semibold text-[#17171D]">Lecture #{{ $recording->session->lecture_number }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Recording Part:</span>
                    <span class="font-bold text-[#7657F6]">Part {{ $recording->part_number }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Student Uploader:</span>
                    <span class="font-semibold text-[#17171D]">{{ $recording->uploader ? $recording->uploader->name : 'Unknown' }} ({{ $recording->uploader ? $recording->uploader->email : '' }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-[#E5E2DC]">
                    <span>Uploaded At:</span>
                    <span class="font-semibold text-[#17171D]">{{ $recording->created_at->format('M d, Y @ h:i A') }}</span>
                </div>
                @if($recording->reviewer)
                    <div class="flex justify-between py-1.5">
                        <span>Reviewed By:</span>
                        <span class="font-semibold text-[#17171D]">{{ $recording->reviewer->name }}</span>
                    </div>
                @endif
            </div>

            @if($recording->rejection_reason)
                <div class="mt-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900">
                    <strong>Rejection Reason on File:</strong>
                    <p class="mt-1">{{ $recording->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Attached Resources -->
        <div class="editorial-card p-6 space-y-4">
            <h3 class="font-display font-bold text-sm text-[#17171D]">Companion Study Resources</h3>
            
            <div class="divide-y divide-[#E5E2DC]">
                @forelse($recording->session->resources as $res)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="{{ $res->icon }}" class="w-4 h-4 text-[#7657F6]"></i>
                            <div>
                                <p class="font-semibold text-[#17171D]">{{ $res->title }}</p>
                                <p class="text-[10px] text-[#777681] uppercase">{{ $res->type }} {{ $res->file_size ? '· ' . $res->file_size : '' }}</p>
                            </div>
                        </div>

                        @if($res->file_path)
                            <a href="{{ asset('storage/' . $res->file_path) }}" download class="text-xs font-semibold text-[#7657F6] hover:underline">
                                Download
                            </a>
                        @elseif($res->external_url)
                            <a href="{{ $res->external_url }}" target="_blank" class="text-xs font-semibold text-[#7657F6] hover:underline">
                                Open Link
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-[#777681] py-4">No additional materials submitted with this session.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
