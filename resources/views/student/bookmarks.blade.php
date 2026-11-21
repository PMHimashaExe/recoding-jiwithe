@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E5E2DC]">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#7657F6] mb-1">
                <i data-lucide="bookmark" class="w-3.5 h-3.5 fill-[#7657F6]"></i>
                <span>Personal Collection</span>
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[#17171D] tracking-tight">
                Saved Lectures
            </h1>
            <p class="text-xs sm:text-sm text-[#777681] mt-1">
                Quick access to bookmarked lectures and exam revision materials.
            </p>
        </div>

        <a href="{{ route('lectures.index') }}" 
           class="px-4 py-2 rounded-xl bg-white hover:bg-[#FAF8F5] text-[#17171D] border border-[#E5E2DC] text-xs font-semibold transition flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="search" class="w-3.5 h-3.5 text-[#777681]"></i>
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
                        <div class="relative aspect-video bg-[#17171D] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-[#17171D]">
                                    {{ $session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/60 text-white border border-white/10">
                                    Lec #{{ $session->lecture_number }}
                                </span>
                            </div>

                            <a href="{{ route('lectures.show', $session) }}" class="w-12 h-12 rounded-full bg-white text-[#17171D] flex items-center justify-center shadow-lg group-hover:scale-110 transition z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5"></i>
                            </a>

                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200 z-10">
                                <span class="truncate max-w-[170px]">{{ $session->batch->batch_name }}</span>
                                <span>{{ $session->lecture_date->format('M j, Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <span class="text-[10px] font-bold text-[#7657F6] uppercase tracking-wider block">{{ $session->subject->subject_name }}</span>
                            <h3 class="font-display font-bold text-base text-[#17171D] group-hover:text-[#7657F6] transition line-clamp-2 leading-snug">
                                {{ $session->title }}
                            </h3>
                            <p class="text-xs text-[#777681] line-clamp-2 leading-relaxed">
                                {{ $session->description ?? 'No extra summary.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer with Remove Bookmark and Watch button -->
                    <div class="px-5 py-3.5 border-t border-[#E5E2DC] bg-[#FAF8F5] flex items-center justify-between">
                        <form action="{{ route('bookmarks.toggle', $session) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1 text-xs text-[#EF4444] hover:underline"
                                    title="Remove from saved">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Remove</span>
                            </button>
                        </form>

                        <a href="{{ route('lectures.show', $session) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#17171D] hover:bg-[#7657F6] text-white text-xs font-semibold transition">
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
            <div class="w-14 h-14 rounded-2xl bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center mx-auto">
                <i data-lucide="bookmark" class="w-7 h-7"></i>
            </div>
            <h3 class="font-display font-bold text-lg text-[#17171D]">No saved lectures yet</h3>
            <p class="text-xs text-[#777681] leading-relaxed">
                When you're browsing the lecture library, click the bookmark icon on any session to save it here for fast revision.
            </p>
            <div class="pt-2">
                <a href="{{ route('lectures.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#7657F6] text-white text-xs font-semibold">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Explore Lecture Library</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
