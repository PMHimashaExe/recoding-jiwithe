@extends('layouts.app')

@section('content')
<div class="space-y-10 py-2">

    <!-- Personalized Welcome Banner -->
    <section class="editorial-card p-6 sm:p-8 bg-gradient-to-r from-[#0D1322] via-[#0F182A] to-[#12162B] border border-[#1E283D] relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-display font-bold text-xl text-white shadow-[0_0_20px_rgba(245,158,11,0.35)] shrink-0 border border-amber-500/30"
                     style="background-color: {{ $user->avatar_color ?? '#D97706' }}">
                    {{ $user->initials }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-display font-extrabold text-2xl text-white tracking-tight">
                            Hello, {{ $user->name }}
                        </h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
                            <i data-lucide="shield-check" class="w-3 h-3 text-emerald-400"></i>
                            <span>Verified</span>
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-[#94A3B8] mt-1">
                        <span class="flex items-center gap-1 font-medium text-cyan-300">
                            <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-amber-400"></i>
                            {{ $user->batch ? $user->batch->batch_name : 'University Student' }}
                        </span>
                        <span>·</span>
                        <span>ID: <strong class="text-white font-mono">{{ $user->student_id_number ?? 'ST-2026' }}</strong></span>
                        <span>·</span>
                        <span class="text-amber-400">All Batches Open Access</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('upload.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-[0_0_15px_rgba(245,158,11,0.3)] hover:shadow-[0_0_20px_rgba(245,158,11,0.5)] flex items-center gap-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>Upload Recording</span>
                </a>
                <a href="{{ route('lectures.index') }}" 
                   class="px-4 py-2.5 rounded-xl bg-[#131D31] hover:bg-[#1A2640] text-cyan-300 border border-cyan-500/30 text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-cyan-400"></i>
                    <span>Browse Library</span>
                </a>
            </div>
        </div>

        <!-- Quick Metrics Sub-Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-[#1E283D] relative z-10">
            <div>
                <span class="text-[11px] text-[#94A3B8] uppercase font-semibold tracking-wider">My Submissions</span>
                <p class="font-display font-bold text-xl text-white mt-0.5">{{ $stats['uploads_count'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#94A3B8] uppercase font-semibold tracking-wider">Approved Videos</span>
                <p class="font-display font-bold text-xl text-emerald-400 mt-0.5">{{ $stats['approved_uploads'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#94A3B8] uppercase font-semibold tracking-wider">Saved Lectures</span>
                <p class="font-display font-bold text-xl text-amber-400 mt-0.5">{{ $stats['bookmarks_count'] }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#94A3B8] uppercase font-semibold tracking-wider">Completed Sessions</span>
                <p class="font-display font-bold text-xl text-cyan-400 mt-0.5">{{ $stats['watched_count'] }}</p>
            </div>
        </div>
    </section>

    <!-- Continue Watching Section -->
    @if($continueWatching->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-cyan-400 shadow-[0_0_8px_#38BDF8]"></div>
                    <h2 class="font-display font-bold text-lg text-white">Continue Watching</h2>
                </div>
                <span class="text-xs text-[#94A3B8]">Pick up where you left off</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($continueWatching as $history)
                    @php
                        $recording = $history->recording;
                        $session = $recording?->session;
                    @endphp
                    @if($session)
                        <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $recording->id]) }}" 
                           class="editorial-card p-4 flex flex-col justify-between group hover:border-cyan-400/50 transition">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="font-bold text-cyan-400 uppercase font-mono">{{ $session->subject->subject_code }}</span>
                                    <span class="text-[#94A3B8]">Part {{ $recording->part_number }}</span>
                                </div>
                                <h3 class="font-display font-semibold text-xs text-white line-clamp-2 group-hover:text-amber-400 transition">
                                    {{ $session->title }}
                                </h3>
                            </div>

                            <div class="mt-4 pt-3 border-t border-[#1E283D] flex items-center justify-between text-[11px] text-[#94A3B8]">
                                <span class="flex items-center gap-1 text-emerald-400 font-medium">
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
                <h2 class="font-display font-bold text-lg text-white">Browse by Subject</h2>
                <p class="text-xs text-[#94A3B8]">Explore recordings grouped by curriculum syllabus</p>
            </div>
            <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300">View All Subjects</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($subjects as $subject)
                <a href="{{ route('lectures.index', ['subject_id' => $subject->id]) }}" 
                   class="editorial-card p-4 flex flex-col items-center text-center group hover:bg-[#131D31] transition duration-150">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 group-hover:bg-cyan-500 group-hover:text-black flex items-center justify-center transition-colors mb-2.5 shadow-[0_0_10px_rgba(56,189,248,0.15)]">
                        <i data-lucide="{{ $subject->icon ?? 'book-open' }}" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold text-amber-400 tracking-wider font-mono">{{ $subject->subject_code }}</span>
                    <h3 class="font-display font-bold text-xs text-white line-clamp-1 mt-0.5">{{ $subject->subject_name }}</h3>
                    <span class="text-[10px] text-[#94A3B8] mt-1">{{ $subject->sessions_count }} {{ Str::plural('lecture', $subject->sessions_count) }}</span>
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
                    <h2 class="font-display font-bold text-lg text-white">Recently Approved Lectures</h2>
                    <p class="text-xs text-[#94A3B8]">Freshly published class recordings across all university batches</p>
                </div>
                <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
                    <span>Full Catalog</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($recentSessions as $session)
                    <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Video Thumbnail Area -->
                            <div class="relative aspect-[16/9] bg-[#04060A] flex items-center justify-center overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D1322] via-[#0D1322]/40 to-transparent"></div>
                                
                                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 z-10">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500 text-black font-mono">
                                        {{ $session->subject->subject_code }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-black/70 text-white border border-white/10">
                                        Lec #{{ $session->lecture_number }}
                                    </span>
                                </div>

                                <div class="absolute top-2.5 right-2.5 z-10">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500 text-black font-bold">
                                        {{ $session->approvedRecordings->count() }} {{ Str::plural('Part', $session->approvedRecordings->count()) }}
                                    </span>
                                </div>

                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)] group-hover:scale-110 transition duration-150 z-10">
                                    <i data-lucide="play" class="w-4 h-4 fill-current ml-0.5 text-white"></i>
                                </div>

                                <div class="absolute bottom-2 left-3 right-3 flex items-center justify-between text-[10px] text-gray-200 z-10">
                                    <span class="truncate max-w-[150px]">{{ $session->batch->batch_name }}</span>
                                    <span>{{ $session->lecture_date->format('M j, Y') }}</span>
                                </div>
                            </div>

                            <div class="p-4 space-y-1.5">
                                <span class="text-[10px] font-semibold text-cyan-400 font-mono uppercase tracking-wider">{{ $session->subject->subject_name }}</span>
                                <h3 class="font-display font-bold text-sm text-white line-clamp-1 group-hover:text-amber-400 transition">
                                    {{ $session->title }}
                                </h3>
                                <p class="text-xs text-[#94A3B8] line-clamp-2 leading-relaxed">
                                    {{ $session->description ?? 'No extra summary.' }}
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-t border-[#1E283D] bg-[#0A0E1A] flex items-center justify-between">
                            <span class="text-[11px] text-[#94A3B8] flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3 text-cyan-400"></i>
                                <span>{{ $session->lecture_date->format('M d') }}</span>
                            </span>
                            <a href="{{ route('lectures.show', $session) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-amber-400 hover:text-amber-300">
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
                    <h2 class="font-display font-bold text-lg text-white">My Submissions</h2>
                    <p class="text-xs text-[#94A3B8]">Live review status</p>
                </div>
                <a href="{{ route('my-uploads') }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300">See All</a>
            </div>

            <div class="editorial-card divide-y divide-[#1E283D]">
                @forelse($myRecentUploads as $upload)
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase font-mono">
                                {{ $upload->session->subject->subject_code }} · Part {{ $upload->part_number }}
                            </span>
                            @if($upload->status === 'approved')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                                    <i data-lucide="check" class="w-2.5 h-2.5"></i> Approved
                                </span>
                            @elseif($upload->status === 'pending')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-2.5 h-2.5"></i> In Review
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30 flex items-center gap-1">
                                    <i data-lucide="x" class="w-2.5 h-2.5"></i> Rejected
                                </span>
                            @endif
                        </div>

                        <h4 class="font-display font-semibold text-xs text-white line-clamp-1">
                            {{ $upload->session->title }}
                        </h4>

                        <div class="flex items-center justify-between text-[11px] text-[#94A3B8] pt-1">
                            <span>Uploaded {{ $upload->created_at->diffForHumans() }}</span>
                            @if($upload->status === 'approved')
                                <a href="{{ route('lectures.show', $upload->session) }}" class="text-amber-400 font-semibold hover:underline">View Live</a>
                            @endif
                        </div>

                        @if($upload->status === 'rejected' && $upload->rejection_reason)
                            <div class="mt-2 p-2 rounded-lg bg-rose-500/10 border border-rose-500/30 text-[11px] text-rose-300">
                                <strong>Reason:</strong> {{ $upload->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-[#94A3B8] space-y-3">
                        <i data-lucide="upload" class="w-8 h-8 text-gray-600 mx-auto"></i>
                        <p>You haven't uploaded any lecture recordings yet.</p>
                        <a href="{{ route('upload.create') }}" class="inline-block px-3 py-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold text-xs">
                            Upload Your First Lecture
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Upload CTA Banner Card -->
            <div class="rounded-2xl bg-[#0F172A] text-white p-5 space-y-3 border border-[#1E283D] shadow-sm relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-500 text-black flex items-center justify-center font-bold">
                        <i data-lucide="video" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-display font-semibold text-xs text-white">Have a missed lecture recording?</h3>
                </div>
                <p class="text-[11px] text-[#94A3B8] leading-relaxed">
                    Help your batchmates by submitting class recordings with companion PDF slides and tutorial files.
                </p>
                <a href="{{ route('upload.create') }}" 
                   class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Share Lecture Recording</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
