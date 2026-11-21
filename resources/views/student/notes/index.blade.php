@extends('layouts.app')

@section('content')
<div class="space-y-6 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#1E283D]">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </span>
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                    My AI Study Notes
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-[#94A3B8] mt-1">
                Your synthesized lecture study guides, active recall flashcards, and exam cram sheets.
            </p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('notes.index') }}" class="relative w-full sm:w-72">
            <input type="text" 
                   name="search" 
                   value="{{ $search }}" 
                   placeholder="Search notes or subjects..." 
                   class="w-full px-3.5 py-2 pl-9 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
            <i data-lucide="search" class="w-4 h-4 text-[#94A3B8] absolute left-3 top-2.5"></i>
        </form>
    </div>

    <!-- Notes Grid -->
    @if($notes->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($notes as $note)
                @php
                    $badge = $note->note_type_badge;
                @endphp
                <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] hover:border-cyan-500/30 p-5 sm:p-6 flex flex-col justify-between group shadow-xl transition-all">
                    <div class="space-y-3">
                        
                        <!-- Top Meta -->
                        <div class="flex items-center justify-between gap-2 text-[10px]">
                            <span class="px-2 py-0.5 rounded font-mono font-bold bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">
                                {{ $note->session->subject->subject_code }}
                            </span>
                            <span class="px-2 py-0.5 rounded font-semibold text-amber-400 bg-amber-500/10 border border-amber-500/20">
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        <!-- Title -->
                        <div>
                            <span class="text-[11px] text-[#94A3B8]">Session #{{ $note->session->lecture_number }} · {{ $note->session->subject->subject_name }}</span>
                            <h3 class="font-display font-bold text-sm text-white line-clamp-2 mt-0.5 group-hover:text-cyan-300 transition">
                                {{ $note->title }}
                            </h3>
                        </div>

                        <!-- Key Takeaways Preview -->
                        @if($note->key_takeaways && count($note->key_takeaways))
                            <div class="space-y-1.5 pt-2 border-t border-[#1E283D]">
                                <span class="text-[10px] uppercase font-bold text-[#94A3B8] tracking-wider">Key Takeaways</span>
                                <ul class="text-[11px] text-slate-300 space-y-1">
                                    @foreach(array_slice($note->key_takeaways, 0, 2) as $point)
                                        <li class="flex items-start gap-1.5 line-clamp-1">
                                            <span class="text-cyan-400 font-bold shrink-0">·</span>
                                            <span class="truncate">{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($note->student_notes)
                            <div class="p-2 rounded-lg bg-[#090D18] border border-amber-500/20 text-[10px] text-amber-300/90 flex items-center gap-1.5">
                                <i data-lucide="edit-3" class="w-3 h-3 text-amber-400 shrink-0"></i>
                                <span class="truncate">Includes {{ Str::words($note->student_notes, 6) }}</span>
                            </div>
                        @endif

                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-4 mt-4 border-t border-[#1E283D] flex items-center justify-between text-xs">
                        <span class="text-[10px] text-[#94A3B8]">{{ $note->updated_at->diffForHumans() }}</span>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('notes.download', $note) }}" 
                               class="p-1.5 rounded-lg border border-[#1E283D] bg-[#090D18] hover:bg-[#131D31] text-[#94A3B8] hover:text-white transition"
                               title="Download Markdown (.md)">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            </a>

                            <a href="{{ route('lectures.show', $note->session) }}" 
                               class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-black font-bold text-xs transition shadow-sm flex items-center gap-1">
                                <span>Study Now</span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $notes->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] p-12 text-center space-y-4 max-w-md mx-auto">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto shadow-[0_0_20px_rgba(245,158,11,0.2)]">
                <i data-lucide="sparkles" class="w-7 h-7"></i>
            </div>
            
            <div class="space-y-1">
                <h3 class="font-display font-bold text-base text-white">No lecture notes generated yet</h3>
                <p class="text-xs text-[#94A3B8]">
                    When you watch lecture recordings, UniReplay can automatically generate comprehensive study guides, exam cram sheets, and flashcards with a single click.
                </p>
            </div>

            <div class="pt-2">
                <a href="{{ route('lectures.index') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-black font-bold text-xs transition shadow-[0_0_15px_rgba(56,189,248,0.3)]">
                    <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i>
                    <span>Browse Lectures & Generate Notes</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
