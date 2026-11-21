@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Header Section with Title & Search Form -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-[#1E283D]">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-cyan-400 mb-1">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                <span>Open University Archive</span>
            </div>
            <h1 class="font-display font-extrabold text-3xl text-white tracking-tight">
                Lecture Library
            </h1>
            <p class="text-xs sm:text-sm text-[#94A3B8] mt-1">
                Access published lecture recordings across all academic batches and subjects.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->canUpload())
                    <a href="{{ route('upload.create') }}" 
                       class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-[0_0_15px_rgba(245,158,11,0.3)] flex items-center gap-1.5 shrink-0">
                        <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                        <span>Upload Recording</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <form method="GET" action="{{ route('lectures.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <!-- Search Keyword Box (5 cols) -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search lecture title, topic, or subject code..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm">
            </div>

            <!-- Batch Filter Dropdown (3 cols) -->
            <div class="sm:col-span-3">
                <select name="batch_id" 
                        onchange="this.form.submit()" 
                        class="w-full px-3 py-2.5 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                            {{ $batch->batch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By Dropdown (2 cols) -->
            <div class="sm:col-span-2">
                <select name="sort" 
                        onchange="this.form.submit()" 
                        class="w-full px-3 py-2.5 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Date</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest Date</option>
                    <option value="lecture_num" {{ $sort === 'lecture_num' ? 'selected' : '' }}>Lecture #</option>
                </select>
            </div>

            <!-- Submit Button (2 cols) -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-sm">
                    Filter
                </button>
                @if($search || $subjectId || $batchId || $dateFilter)
                    <a href="{{ route('lectures.index') }}" class="p-2.5 rounded-xl bg-[#0D1322] border border-[#1E283D] text-[#94A3B8] hover:text-white transition" title="Clear Filters">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>

        </div>

        <!-- Subject Filter Pills Horizontal Carousel -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs">
            <span class="text-[#94A3B8] font-semibold uppercase text-[10px] tracking-wider shrink-0 mr-1">Subject:</span>
            
            <a href="{{ route('lectures.index', array_merge(request()->except('subject_id', 'page'))) }}" 
               class="px-3 py-1.5 rounded-full font-medium transition shrink-0 {{ !$subjectId ? 'bg-cyan-500 text-black font-bold shadow-[0_0_10px_rgba(56,189,248,0.4)]' : 'bg-[#0D1322] text-[#94A3B8] hover:text-white border border-[#1E283D]' }}">
                All Subjects
            </a>

            @foreach($subjects as $sub)
                <a href="{{ route('lectures.index', array_merge(request()->except('page'), ['subject_id' => $sub->id])) }}" 
                   class="px-3 py-1.5 rounded-full font-medium transition shrink-0 flex items-center gap-1.5 {{ $subjectId == $sub->id ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-[0_0_12px_rgba(245,158,11,0.4)] font-bold' : 'bg-[#0D1322] text-[#94A3B8] hover:text-white border border-[#1E283D]' }}">
                    <span class="font-mono">{{ $sub->subject_code }}</span>
                    <span class="opacity-70 text-[10px]">· {{ $sub->subject_name }}</span>
                </a>
            @endforeach
        </div>
    </form>

    <!-- Lecture Grid -->
    @if($sessions->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sessions as $session)
                <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail Player Teaser -->
                        <div class="relative aspect-video bg-[#04060A] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D1322] via-[#0D1322]/40 to-transparent"></div>

                            <!-- Subject badge -->
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-cyan-500 text-black font-mono shadow-sm">
                                    {{ $session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/70 text-white border border-white/10">
                                    Session #{{ $session->lecture_number }}
                                </span>
                            </div>

                            <!-- Parts count badge -->
                            <div class="absolute top-3 right-3 z-10">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-black shadow-md flex items-center gap-1">
                                    <i data-lucide="layers" class="w-3 h-3"></i>
                                    <span>{{ $session->approvedRecordings->count() }} {{ Str::plural('Part', $session->approvedRecordings->count()) }}</span>
                                </span>
                            </div>

                            <!-- Play Action Button -->
                            <a href="{{ route('lectures.show', $session) }}" class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_20px_rgba(245,158,11,0.5)] transform group-hover:scale-110 transition duration-150 z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5 text-white"></i>
                            </a>

                            <!-- Card Bottom Overlay Bar -->
                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200 z-10">
                                <span class="truncate max-w-[170px] font-medium">{{ $session->batch->batch_name }}</span>
                                <span>{{ $session->lecture_date->format('M j, Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="p-5 space-y-2">
                            <span class="text-[10px] font-bold text-cyan-400 font-mono uppercase tracking-wider block">
                                {{ $session->subject->subject_name }}
                            </span>

                            <a href="{{ route('lectures.show', $session) }}">
                                <h3 class="font-display font-bold text-base text-white group-hover:text-amber-400 transition line-clamp-2 leading-snug">
                                    {{ $session->title }}
                                </h3>
                            </a>

                            <p class="text-xs text-[#94A3B8] line-clamp-2 leading-relaxed">
                                {{ $session->description ?? 'No extra session description available.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Action Footer -->
                    <div class="px-5 py-3.5 border-t border-[#1E283D] bg-[#0A0E1A] flex items-center justify-between">
                        <div class="flex items-center gap-2 text-[11px] text-[#94A3B8]">
                            @php
                                $uploader = $session->approvedRecordings->first()?->uploader;
                            @endphp
                            @if($uploader)
                                <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[9px] text-white"
                                     style="background-color: {{ $uploader->avatar_color ?? '#0284C7' }}">
                                    {{ $uploader->initials }}
                                </div>
                                <span class="truncate max-w-[100px]">{{ $uploader->name }}</span>
                            @else
                                <i data-lucide="user" class="w-3.5 h-3.5 text-cyan-400"></i>
                                <span>Verified Student</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Bookmark button -->
                            <form action="{{ route('bookmarks.toggle', $session) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="p-1.5 rounded-lg text-[#94A3B8] hover:text-amber-400 hover:bg-white/5 transition"
                                        title="{{ $session->isBookmarkedBy(auth()->user()) ? 'Remove bookmark' : 'Bookmark this lecture' }}">
                                    <i data-lucide="bookmark" class="w-4 h-4 {{ $session->isBookmarkedBy(auth()->user()) ? 'fill-amber-400 text-amber-400' : '' }}"></i>
                                </button>
                            </form>

                            <a href="{{ route('lectures.show', $session) }}" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#131D31] hover:bg-amber-500 hover:text-black text-cyan-300 text-xs font-semibold transition border border-cyan-500/30">
                                <span>Watch</span>
                                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-6">
            {{ $sessions->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="editorial-card p-12 text-center space-y-4 max-w-lg mx-auto">
            <div class="w-14 h-14 rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center mx-auto">
                <i data-lucide="search-x" class="w-7 h-7"></i>
            </div>
            <h3 class="font-display font-bold text-lg text-white">No lecture recordings match your filters</h3>
            <p class="text-xs text-[#94A3B8] leading-relaxed">
                Try clearing your search terms or selecting another subject or batch to explore other published recordings.
            </p>
            <div class="pt-2">
                <a href="{{ route('lectures.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset All Filters</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
