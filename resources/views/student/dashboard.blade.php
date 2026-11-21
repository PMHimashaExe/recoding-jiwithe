@extends('layouts.app')

@section('content')
<div class="space-y-10 py-2">

    <!-- Personalized Welcome Banner -->
    <section class="editorial-card p-6 sm:p-8 bg-gradient-to-r from-white via-white to-[#F0EDFF]/40">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-display font-bold text-xl text-white shadow-md shrink-0"
                     style="background-color: {{ $user->avatar_color ?? '#7657F6' }}">
                    {{ $user->initials }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-display font-extrabold text-2xl text-[#17171D] tracking-tight">
                            Hello, {{ $user->name }}
                        </h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF8F4] text-[#27B58A] border border-[#27B58A]/20 uppercase tracking-wider">
                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                            <span>Verified</span>
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-[#777681] mt-1">
                        <span class="flex items-center gap-1 font-medium text-[#17171D]">
                            <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-[#7657F6]"></i>
                            {{ $user->batch ? $user->batch->batch_name : 'University Student' }}
                        </span>
                        <span>·</span>
                        <span>ID: <strong class="text-[#17171D]">{{ $user->student_id_number ?? 'ST-2026' }}</strong></span>
                        <span>·</span>
                        <span>All Batches Open Access</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('upload.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition shadow-sm hover:shadow flex items-center gap-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>Upload Recording</span>
                </a>
                <a href="{{ route('lectures.index') }}" 
                   class="px-4 py-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] text-[#17171D] border border-[#E5E2DC] text-xs font-semibold transition flex items-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-[#777681]"></i>
                    <span>Browse Library</span>
                </a>
            </div>
        </div>

        <!-- Quick Metrics Sub-Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-[#E5E2DC]">
            <div>
                <span class="text-[11px] text-[#777681] uppercase font-semibold tracking-wider">My Submissions</span>
                <p class="font-display font-bold text-xl text-[#17171D] mt-0.5">{{ $stats['uploads_count'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#777681] uppercase font-semibold tracking-wider">Approved Videos</span>
                <p class="font-display font-bold text-xl text-[#27B58A] mt-0.5">{{ $stats['approved_uploads'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#777681] uppercase font-semibold tracking-wider">Saved Lectures</span>
                <p class="font-display font-bold text-xl text-[#7657F6] mt-0.5">{{ $stats['bookmarks_count'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#777681] uppercase font-semibold tracking-wider">Completed Sessions</span>
                <p class="font-display font-bold text-xl text-[#17171D] mt-0.5">{{ $stats['watched_count'] }}</p>
            </div>
        </div>
    </section>

    <!-- Continue Watching Section -->
    @if($continueWatching->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#7657F6]"></div>
                    <h2 class="font-display font-bold text-lg text-[#17171D]">Continue Watching</h2>
                </div>
                <span class="text-xs text-[#777681]">Pick up where you left off</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($continueWatching as $history)
                    @php
                        $recording = $history->recording;
                        $session = $recording?->session;
                    @endphp
                    @if($session)
                        <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $recording->id]) }}" 
                           class="editorial-card p-4 flex flex-col justify-between group hover:border-[#7657F6]/40 transition">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="font-bold text-[#7657F6] uppercase">{{ $session->subject->subject_code }}</span>
                                    <span class="text-[#777681]">Part {{ $recording->part_number }}</span>
                                </div>
                                <h3 class="font-display font-semibold text-xs text-[#17171D] line-clamp-2 group-hover:text-[#7657F6] transition">
                                    {{ $session->title }}
                                </h3>
                            </div>

                            <div class="mt-4 pt-3 border-t border-[#E5E2DC] flex items-center justify-between text-[11px] text-[#777681]">
                                <span class="flex items-center gap-1 text-[#27B58A] font-medium">
                                    <i data-lucide="play" class="w-3 h-3 fill-current"></i>
                                    <span>Resume Part {{ $recording->part_number }}</span>
                                </span>
                                <span>{{ $recording->duration ?? '45m' }}</span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    <!-- Browse by Academic Subject -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display font-bold text-lg text-[#17171D]">Browse by Subject</h2>
                <p class="text-xs text-[#777681]">Explore recordings grouped by curriculum syllabus</p>
            </div>
            <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-[#7657F6] hover:underline">View All Subjects</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($subjects as $subject)
                <a href="{{ route('lectures.index', ['subject_id' => $subject->id]) }}" 
                   class="editorial-card p-4 flex flex-col items-center text-center group hover:bg-[#17171D] hover:text-white transition duration-150">
                    <div class="w-10 h-10 rounded-xl bg-[#F0EDFF] text-[#7657F6] group-hover:bg-[#7657F6] group-hover:text-white flex items-center justify-center transition-colors mb-2.5">
                        <i data-lucide="{{ $subject->icon ?? 'book-open' }}" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold text-[#7657F6] group-hover:text-[#B7A7FF] tracking-wider">{{ $subject->subject_code }}</span>
                    <h3 class="font-display font-bold text-xs text-[#17171D] group-hover:text-white line-clamp-1 mt-0.5">{{ $subject->subject_name }}</h3>
                    <span class="text-[10px] text-[#777681] group-hover:text-gray-400 mt-1">{{ $subject->sessions_count }} {{ Str::plural('lecture', $subject->sessions_count) }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Main 2-Column Section: Recently Published & My Recent Submissions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 8 Columns: Recently Published Lectures -->
        <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display font-bold text-lg text-[#17171D]">Recently Approved Lectures</h2>
                    <p class="text-xs text-[#777681]">Freshly published class recordings across all university batches</p>
                </div>
                <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-[#7657F6] hover:underline flex items-center gap-1">
                    <span>Full Catalog</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($recentSessions as $session)
                    <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Video Thumbnail Area -->
                            <div class="relative aspect-[16/9] bg-[#17171D] flex items-center justify-center overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                
                                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-[#17171D]">
                                        {{ $session->subject->subject_code }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-black/60 text-white border border-white/10">
                                        Lec #{{ $session->lecture_number }}
                                    </span>
                                </div>

                                <div class="absolute top-2.5 right-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#7657F6] text-white">
                                        {{ $session->approvedRecordings->count() }} {{ Str::plural('Part', $session->approvedRecordings->count()) }}
                                    </span>
                                </div>

                                <div class="w-10 h-10 rounded-full bg-white text-[#17171D] flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-150">
                                    <i data-lucide="play" class="w-4 h-4 fill-current ml-0.5"></i>
                                </div>

                                <div class="absolute bottom-2 left-3 right-3 flex items-center justify-between text-[10px] text-gray-200">
                                    <span class="truncate max-w-[150px]">{{ $session->batch->batch_name }}</span>
                                    <span>{{ $session->lecture_date->format('M j, Y') }}</span>
                                </div>
                            </div>

                            <div class="p-4 space-y-1.5">
                                <span class="text-[10px] font-semibold text-[#7657F6] uppercase tracking-wider">{{ $session->subject->subject_name }}</span>
                                <h3 class="font-display font-bold text-sm text-[#17171D] line-clamp-1 group-hover:text-[#7657F6] transition">
                                    {{ $session->title }}
                                </h3>
                                <p class="text-xs text-[#777681] line-clamp-2 leading-relaxed">
                                    {{ $session->description ?? 'No extra summary.' }}
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-t border-[#E5E2DC] bg-[#FAF8F5] flex items-center justify-between">
                            <span class="text-[11px] text-[#777681] flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                <span>{{ $session->lecture_date->format('M d') }}</span>
                            </span>
                            <a href="{{ route('lectures.show', $session) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#7657F6] hover:underline">
                                <span>Watch Lecture</span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right 4 Columns: My Recent Uploads & Status -->
        <div class="lg:col-span-4 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display font-bold text-lg text-[#17171D]">My Submissions</h2>
                    <p class="text-xs text-[#777681]">Live review status</p>
                </div>
                <a href="{{ route('my-uploads') }}" class="text-xs font-semibold text-[#7657F6] hover:underline">See All</a>
            </div>

            <div class="editorial-card divide-y divide-[#E5E2DC]">
                @forelse($myRecentUploads as $upload)
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#777681] uppercase">
                                {{ $upload->session->subject->subject_code }} · Part {{ $upload->part_number }}
                            </span>
                            @if($upload->status === 'approved')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EBF8F4] text-[#27B58A] border border-[#27B58A]/30 flex items-center gap-1">
                                    <i data-lucide="check" class="w-2.5 h-2.5"></i> Approved
                                </span>
                            @elseif($upload->status === 'pending')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-2.5 h-2.5"></i> In Review
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                                    <i data-lucide="x" class="w-2.5 h-2.5"></i> Rejected
                                </span>
                            @endif
                        </div>

                        <h4 class="font-display font-semibold text-xs text-[#17171D] line-clamp-1">
                            {{ $upload->session->title }}
                        </h4>

                        <div class="flex items-center justify-between text-[11px] text-[#777681] pt-1">
                            <span>Uploaded {{ $upload->created_at->diffForHumans() }}</span>
                            @if($upload->status === 'approved')
                                <a href="{{ route('lectures.show', $upload->session) }}" class="text-[#7657F6] font-semibold hover:underline">View Live</a>
                            @endif
                        </div>

                        @if($upload->status === 'rejected' && $upload->rejection_reason)
                            <div class="mt-2 p-2 rounded-lg bg-rose-50/70 border border-rose-200 text-[11px] text-rose-800">
                                <strong>Reason:</strong> {{ $upload->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#777681] space-y-3">
                        <i data-lucide="upload" class="w-8 h-8 text-gray-300 mx-auto"></i>
                        <p>You haven't uploaded any lecture recordings yet.</p>
                        <a href="{{ route('upload.create') }}" class="inline-block px-3 py-1.5 rounded-lg bg-[#7657F6] text-white font-semibold text-xs">
                            Upload Your First Lecture
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Upload CTA Banner Card -->
            <div class="rounded-2xl bg-[#17171D] text-white p-5 space-y-3 border border-[#24242D] shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-[#7657F6] text-white flex items-center justify-center">
                        <i data-lucide="video" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="font-display font-semibold text-xs text-white">Have a missed lecture recording?</h3>
                </div>
                <p class="text-[11px] text-gray-300 leading-relaxed">
                    Help your batchmates by submitting class recordings with companion PDF slides and tutorial files.
                </p>
                <a href="{{ route('upload.create') }}" 
                   class="w-full py-2 rounded-xl bg-white hover:bg-gray-100 text-[#17171D] text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Share Lecture Recording</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
