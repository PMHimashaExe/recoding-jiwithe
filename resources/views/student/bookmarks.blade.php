@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#1E283D]">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-amber-400 mb-1">
                <i data-lucide="bookmark" class="w-3.5 h-3.5 fill-amber-400"></i>
                <span>Personal Collection</span>
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                Saved Lectures
            </h1>
            <p class="text-xs sm:text-sm text-[#94A3B8] mt-1">
                Quick access to bookmarked lectures and exam revision materials.
            </p>
        </div>

        <a href="{{ route('lectures.index') }}" 
           class="px-4 py-2 rounded-xl bg-[#0D1322] hover:bg-[#131D31] text-cyan-300 border border-cyan-500/30 text-xs font-semibold transition flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="search" class="w-3.5 h-3.5 text-cyan-400"></i>
            <span>Browse More Lectures</span>
        </a>
    </div>

    <!-- Bookmarks Grid -->
    @if($bookmarkedSessions->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookmarkedSessions as $session)
                <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail -->
                        <div class="relative aspect-video bg-[#04060A] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D1322] via-[#0D1322]/40 to-transparent"></div>
                            
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500 text-black font-mono">
                                    {{ $session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/70 text-white border border-white/10">
                                    Lec #{{ $session->lecture_number }}
                                </span>
                            </div>

                            <a href="{{ route('lectures.show', $session) }}" class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_20px_rgba(245,158,11,0.5)] group-hover:scale-110 transition z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5 text-white"></i>
                            </a>

                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200 z-10">
                                <span class="truncate max-w-[170px]">{{ $session->batch->batch_name }}</span>
                                <span>{{ $session->lecture_date->format('M j, Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block font-mono">{{ $session->subject->subject_name }}</span>
                            <h3 class="font-display font-bold text-base text-white group-hover:text-amber-400 transition line-clamp-2 leading-snug">
                                {{ $session->title }}
                            </h3>
                            <p class="text-xs text-[#94A3B8] line-clamp-2 leading-relaxed">
                                {{ $session->description ?? 'No extra summary.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer with Remove Bookmark and Watch button -->
                    <div class="px-5 py-3.5 border-t border-[#1E283D] bg-[#0A0E1A] flex items-center justify-between">
                        <form action="{{ route('bookmarks.toggle', $session) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1 text-xs text-rose-400 hover:text-rose-300"
                                    title="Remove from saved">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Remove</span>
                            </button>
                        </form>

                        <a href="{{ route('lectures.show', $session) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#131D31] hover:bg-amber-500 hover:text-black text-cyan-300 text-xs font-semibold transition border border-cyan-500/30">
                            <span>Watch</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $bookmarkedSessions->links() }}
        </div>
    @else
        <div class="editorial-card p-12 text-center space-y-4 max-w-md mx-auto">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mx-auto shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                <i data-lucide="bookmark" class="w-7 h-7"></i>
            </div>
            <h3 class="font-display font-bold text-lg text-white">No saved lectures yet</h3>
            <p class="text-xs text-[#94A3B8] leading-relaxed">
                When you're browsing the lecture library, click the bookmark icon on any session to save it here for fast revision.
            </p>
            <div class="pt-2">
                <a href="{{ route('lectures.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Explore Lecture Library</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
