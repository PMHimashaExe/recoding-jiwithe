@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Top Navigation Breadcrumbs & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('lectures.index', ['subject_id' => $session->subject_id]) }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#94A3B8] hover:text-white transition">
            <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
            <span>Back to {{ $session->subject->subject_code }} Lectures</span>
        </a>

        <!-- Bookmark Button -->
        <form action="{{ route('bookmarks.toggle', $session) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#1E283D] bg-[#0D1322] hover:bg-[#131D31] text-xs font-semibold text-white transition shadow-sm">
                <i data-lucide="bookmark" class="w-4 h-4 {{ $isBookmarked ? 'fill-amber-400 text-amber-400' : 'text-[#94A3B8]' }}"></i>
                <span>{{ $isBookmarked ? 'Saved in Bookmarks' : 'Bookmark Lecture' }}</span>
            </button>
        </form>
    </div>

    <!-- Main Player Theater Area (Matching the Hero Cyber Screen) -->
    <div class="rounded-3xl bg-[#090D18] text-white overflow-hidden border border-[#1E283D] shadow-[0_20px_50px_rgba(0,0,0,0.8)]">
        <div class="p-4 sm:p-6 lg:p-8 space-y-4">
            
            <!-- Video Player Element with Cyber Frame -->
            <div class="relative aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-inner flex items-center justify-center border border-cyan-500/20">
                @if($activeRecording)
                    @if($activeRecording->drive_file_id)
                        <iframe id="lectureDrivePlayer"
                                src="https://drive.google.com/file/d/{{ $activeRecording->drive_file_id }}/preview" 
                                class="w-full h-full border-0" 
                                allow="autoplay; encrypted-media" 
                                allowfullscreen>
                        </iframe>
                    @else
                        <video id="lecturePlayer"
                               controls 
                               preload="metadata"
                               class="w-full h-full object-contain"
                               controlsList="nodownload">
                            <source src="{{ route('recordings.stream', $activeRecording) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                @else
                    <div class="text-center p-8 space-y-2">
                        <i data-lucide="video-off" class="w-8 h-8 text-gray-500 mx-auto"></i>
                        <p class="text-sm text-gray-400">No approved recording stream currently available.</p>
                    </div>
                @endif
            </div>

            <!-- Parts Selector & Controls Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-white/10">
                
                <!-- Part Switcher Tabs -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Session Parts:</span>
                    @foreach($approvedRecordings as $rec)
                        @php
                            $isActive = $activeRecording && $activeRecording->id === $rec->id;
                        @endphp
                        <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $rec->id]) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $isActive ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-[0_0_15px_rgba(245,158,11,0.4)] ring-2 ring-amber-400/50' : 'bg-[#0D1322] hover:bg-[#131D31] text-[#94A3B8] border border-[#1E283D]' }}">
                            <i data-lucide="{{ $isActive ? 'play' : 'video' }}" class="w-3.5 h-3.5 {{ $isActive ? 'fill-current' : '' }}"></i>
                            <span>Part {{ $rec->part_number }}</span>
                            @if($rec->duration)
                                <span class="opacity-75 text-[10px]">({{ $rec->duration }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Previous / Next Part Buttons & AI Note CTA -->
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="#smart-notes" 
                       class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-400 text-black text-xs font-bold transition flex items-center gap-1.5 shadow-[0_0_15px_rgba(245,158,11,0.35)]">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-black"></i>
                        <span>AI Study Note</span>
                    </a>

                    @if($activeRecording)
                        @php
                            $currentIndex = $approvedRecordings->search(fn($r) => $r->id === $activeRecording->id);
                            $prevRec = $currentIndex > 0 ? $approvedRecordings->get($currentIndex - 1) : null;
                            $nextRec = $currentIndex < ($approvedRecordings->count() - 1) ? $approvedRecordings->get($currentIndex + 1) : null;
                        @endphp
                        @if($prevRec)
                            <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $prevRec->id]) }}" 
                               class="px-3 py-1.5 rounded-xl bg-[#0D1322] hover:bg-[#131D31] text-[#94A3B8] text-xs font-medium border border-[#1E283D] transition flex items-center gap-1">
                                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                <span>Part {{ $prevRec->part_number }}</span>
                            </a>
                        @endif

                        @if($nextRec)
                            <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $nextRec->id]) }}" 
                               class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-black text-xs font-bold transition flex items-center gap-1 shadow-[0_0_15px_rgba(56,189,248,0.3)]">
                                <span>Next: Part {{ $nextRec->part_number }}</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- 2-Column Info & Resources Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 8 Columns: Lecture Details & Overview -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="editorial-card p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 font-mono">
                        {{ $session->subject->subject_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-[#131D31] text-amber-400 border border-amber-500/30">
                        Lecture Session #{{ $session->lecture_number }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-[#131D31] text-[#94A3B8] border border-[#1E283D]">
                        {{ $session->batch->batch_name }}
                    </span>
                </div>

                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight leading-snug">
                    {{ $session->title }}
                </h1>

                <!-- Metadata Row -->
                <div class="flex flex-wrap items-center gap-4 text-xs text-[#94A3B8] pt-2 pb-4 border-b border-[#1E283D]">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-cyan-400"></i>
                        <span>Date: <strong class="text-white">{{ $session->lecture_date->format('F d, Y') }}</strong></span>
                    </span>
                    <span>·</span>
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="layers" class="w-4 h-4 text-amber-400"></i>
                        <span>Total Parts: <strong class="text-white">{{ $session->approvedRecordings->count() }}</strong></span>
                    </span>
                    <span>·</span>
                    @if($activeRecording && $activeRecording->uploader)
                        <span class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[9px] text-white"
                                 style="background-color: {{ $activeRecording->uploader->avatar_color ?? '#0284C7' }}">
                                {{ $activeRecording->uploader->initials }}
                            </div>
                            <span>Uploaded by: <strong class="text-white">{{ $activeRecording->uploader->name }}</strong></span>
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h3 class="font-display font-bold text-sm text-white">Lecture Syllabus & Description</h3>
                    <p class="text-xs sm:text-sm text-[#94A3B8] leading-relaxed whitespace-pre-line">
                        {{ $session->description ?? 'No detailed description provided for this session.' }}
                    </p>
                </div>

                <!-- Academic Integrity & Verification Notice -->
                <div class="mt-6 p-4 rounded-xl bg-[#09101E] border border-cyan-500/20 flex items-start gap-3 text-xs text-[#94A3B8]">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-semibold text-white">Faculty Verified Recording</span>
                        <p class="mt-0.5">This recording was reviewed and approved for publication by university administration under the UniReplay academic honor code.</p>
                    </div>
                </div>
            </div>

            <!-- AI Smart Study Note & Exam Prep Engine -->
            <div id="smart-notes" 
                 x-data="lectureNoteApp()" 
                 class="rounded-3xl bg-[#090D18] border border-cyan-500/30 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.8)] scroll-mt-6">
                
                <!-- Engine Header Bar -->
                <div class="px-6 py-5 bg-gradient-to-r from-[#0D1426] via-[#090D18] to-[#0D1426] border-b border-[#1E283D] flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/10 border border-amber-500/40 text-amber-400 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.25)]">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-display font-extrabold text-base sm:text-lg text-white">
                                    AI Smart Lecture Notes
                                </h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30 font-mono">
                                    AUTOPILOT
                                </span>
                            </div>
                            <p class="text-xs text-[#94A3B8]">
                                Auto-synthesized academic notes, rapid exam cram sheets, and flashcards.
                            </p>
                        </div>
                    </div>

                    <!-- Right Quick Actions when note exists -->
                    <template x-if="note">
                        <div class="flex items-center gap-2 flex-wrap">
                            <button @click="copyNote()" 
                                    class="px-3 py-1.5 rounded-xl border border-[#1E283D] bg-[#0D1322] hover:bg-[#131D31] text-xs font-semibold text-slate-300 hover:text-white transition flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="copy" class="w-3.5 h-3.5 text-cyan-400" x-show="!copied"></i>
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400" x-show="copied" x-cloak></i>
                                <span x-text="copied ? 'Copied to Clipboard!' : 'Copy Note'"></span>
                            </button>

                            <a :href="'/notes/' + note.id + '/download'" 
                               class="px-3 py-1.5 rounded-xl border border-[#1E283D] bg-[#0D1322] hover:bg-[#131D31] text-xs font-semibold text-slate-300 hover:text-white transition flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="download" class="w-3.5 h-3.5 text-amber-400"></i>
                                <span>Export (.md)</span>
                            </a>

                            <button @click="showFormatModal = true" 
                                    class="px-3 py-1.5 rounded-xl bg-[#131D31] hover:bg-[#1A2640] border border-cyan-500/30 text-xs font-semibold text-cyan-300 transition flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span>Switch Format</span>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Engine Body -->
                <div class="p-6 sm:p-8 space-y-6">

                    <!-- Generation Loading State -->
                    <div x-show="loading" x-cloak class="py-14 text-center space-y-5">
                        <div class="relative w-20 h-20 mx-auto flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-500/20 animate-ping"></div>
                            <div class="w-16 h-16 rounded-full border-2 border-t-cyan-400 border-r-amber-400 border-b-transparent border-l-transparent animate-spin"></div>
                            <div class="absolute w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-display font-bold text-base text-white" x-text="statusMessages[statusIndex]"></h4>
                            <p class="text-xs text-[#94A3B8]">Extracting core topics, formulas, definitions, and probable exam questions...</p>
                        </div>
                    </div>

                    <!-- Note Empty / Generate Initial Note State -->
                    <div x-show="!note && !loading" class="space-y-6">
                        <div class="text-center max-w-xl mx-auto space-y-2">
                            <h4 class="font-display font-bold text-lg text-white">
                                Ready to generate notes for Session #{{ $session->lecture_number }}?
                            </h4>
                            <p class="text-xs text-[#94A3B8]">
                                Select the format that matches your study session goals. UniReplay will synthesize the lecture content into an organized, exam-ready document.
                            </p>
                        </div>

                        <!-- 3 Note Type Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            
                            <!-- Option 1: Comprehensive Study Guide -->
                            <div @click="selectedType = 'summary'" 
                                 :class="selectedType === 'summary' ? 'ring-2 ring-cyan-400 bg-[#0D1426] border-cyan-500/50' : 'bg-[#0D1322] border-[#1E283D] opacity-80 hover:opacity-100'"
                                 class="p-4 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-3 group">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl">📚</span>
                                        <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                              :class="selectedType === 'summary' ? 'border-cyan-400 bg-cyan-400 text-black' : 'border-slate-600'">
                                            <i data-lucide="check" class="w-3 h-3 stroke-[3]" x-show="selectedType === 'summary'"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-display font-bold text-sm text-white group-hover:text-cyan-300">Comprehensive Study Guide</h5>
                                    <p class="text-[11px] text-[#94A3B8] leading-relaxed">
                                        Detailed conceptual deep dive, architectural flows, formal definitions, and model exam answers.
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider">Best for Mid-terms & Finals</span>
                            </div>

                            <!-- Option 2: Rapid Cram Sheet -->
                            <div @click="selectedType = 'cheat_sheet'" 
                                 :class="selectedType === 'cheat_sheet' ? 'ring-2 ring-amber-400 bg-[#0D1426] border-amber-500/50' : 'bg-[#0D1322] border-[#1E283D] opacity-80 hover:opacity-100'"
                                 class="p-4 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-3 group">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl">⚡</span>
                                        <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                              :class="selectedType === 'cheat_sheet' ? 'border-amber-400 bg-amber-400 text-black' : 'border-slate-600'">
                                            <i data-lucide="check" class="w-3 h-3 stroke-[3]" x-show="selectedType === 'cheat_sheet'"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-display font-bold text-sm text-white group-hover:text-amber-300">Rapid Exam Cram Sheet</h5>
                                    <p class="text-[11px] text-[#94A3B8] leading-relaxed">
                                        Quick formulas, syntax matrix, critical traps, gotchas, and high-yield do's and don'ts.
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">5-Minute Revision</span>
                            </div>

                            <!-- Option 3: Flashcards & Q&A -->
                            <div @click="selectedType = 'quiz_flashcards'" 
                                 :class="selectedType === 'quiz_flashcards' ? 'ring-2 ring-purple-400 bg-[#0D1426] border-purple-500/50' : 'bg-[#0D1322] border-[#1E283D] opacity-80 hover:opacity-100'"
                                 class="p-4 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-3 group">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl">❓</span>
                                        <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                              :class="selectedType === 'quiz_flashcards' ? 'border-purple-400 bg-purple-400 text-black' : 'border-slate-600'">
                                            <i data-lucide="check" class="w-3 h-3 stroke-[3]" x-show="selectedType === 'quiz_flashcards'"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-display font-bold text-sm text-white group-hover:text-purple-300">Q&A & Active Flashcards</h5>
                                    <p class="text-[11px] text-[#94A3B8] leading-relaxed">
                                        Active recall test questions, True/False concept verification, and practical viva scenarios.
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Active Memory Recall</span>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-2">
                            <button @click="generateNote()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-400 text-black font-extrabold text-sm transition shadow-[0_0_25px_rgba(245,158,11,0.4)]">
                                <i data-lucide="sparkles" class="w-4 h-4 stroke-[2.5]"></i>
                                <span>Generate Lecture Note Now</span>
                            </button>
                        </div>
                    </div>

                    <!-- Note Display State (When note exists) -->
                    <div x-show="note && !loading" x-cloak class="space-y-6">
                        
                        <!-- Note Title & Format Badge -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#1E283D]">
                            <div>
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold"
                                      :class="note?.note_type === 'cheat_sheet' ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30' : (note?.note_type === 'quiz_flashcards' ? 'bg-purple-500/15 text-purple-400 border border-purple-500/30' : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30')"
                                      x-text="note?.badge?.label || 'Academic Study Guide'"></span>
                                <h2 class="font-display font-bold text-xl sm:text-2xl text-white mt-1.5" x-text="note?.title"></h2>
                                <p class="text-[11px] text-[#94A3B8] mt-0.5">
                                    Last synthesized <span x-text="note?.updated_at"></span> · Saved to your personal note library
                                </p>
                            </div>
                        </div>

                        <!-- Key Takeaways Highlight Box -->
                        <template x-if="note?.key_takeaways && note.key_takeaways.length">
                            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-cyan-950/40 via-[#0D1426] to-[#090D18] border border-cyan-500/30 space-y-2.5">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="lightbulb" class="w-4 h-4 text-amber-400"></i>
                                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-cyan-300">Executive Key Takeaways</h4>
                                </div>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-slate-200">
                                    <template x-for="(point, idx) in note.key_takeaways" :key="idx">
                                        <li class="flex items-start gap-2 bg-[#090D18]/70 p-2.5 rounded-xl border border-white/5">
                                            <span class="w-4 h-4 rounded-full bg-cyan-500/20 text-cyan-300 font-mono text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5" x-text="idx + 1"></span>
                                            <span class="leading-relaxed" x-text="point"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>

                        <!-- Rendered Markdown Content Container -->
                        <div class="prose prose-invert max-w-none text-xs sm:text-sm text-slate-300 leading-relaxed space-y-4 font-normal"
                             x-html="renderMarkdown(note?.content || '')">
                        </div>

                        <!-- Personal Student Annotations Drawer -->
                        <div class="pt-6 border-t border-[#1E283D] space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="edit-3" class="w-4 h-4 text-amber-400"></i>
                                    <h4 class="font-display font-bold text-sm text-white">My Personal Reflections & Exam Reminders</h4>
                                </div>
                                <button @click="showAnnotationsEditor = !showAnnotationsEditor" 
                                        class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 transition">
                                    <span x-text="showAnnotationsEditor ? 'Hide Editor' : 'Edit My Notes'"></span>
                                </button>
                            </div>

                            <!-- Display saved student notes -->
                            <div x-show="!showAnnotationsEditor && studentNotes" class="p-4 rounded-xl bg-[#0D1426] border border-amber-500/30 text-xs text-amber-200/90 whitespace-pre-wrap">
                                <p x-text="studentNotes"></p>
                            </div>

                            <!-- Annotations Textarea Editor -->
                            <div x-show="showAnnotationsEditor || !studentNotes" class="space-y-3">
                                <textarea x-model="studentNotes" 
                                          rows="3" 
                                          placeholder="Type your personal insights, lecture timestamp reminders, or lecturer hints here (e.g. 'Lecturer emphasized Slide 14 will be in Mid-term Part B')..." 
                                          class="w-full p-3.5 rounded-xl border border-[#1E283D] bg-[#070913] text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 leading-relaxed"></textarea>

                                <div class="flex items-center justify-end gap-2">
                                    <button @click="saveAnnotations()" 
                                            :disabled="savingAnnotations"
                                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-black font-bold text-xs transition shadow-sm flex items-center gap-1.5">
                                        <i data-lucide="check" class="w-3.5 h-3.5" x-show="!savingAnnotations"></i>
                                        <span x-text="savingAnnotations ? 'Saving...' : 'Save Annotations'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Format Switch Modal -->
                <div x-show="showFormatModal" 
                     x-cloak 
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                    <div @click.away="showFormatModal = false" class="bg-[#0D1322] border border-[#1E283D] rounded-2xl max-w-lg w-full p-6 text-left space-y-4 shadow-2xl">
                        <div class="flex items-center justify-between">
                            <h3 class="font-display font-bold text-base text-white">Switch Note Format</h3>
                            <button @click="showFormatModal = false" class="text-slate-400 hover:text-white">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-xs text-[#94A3B8]">
                            Select a new format to re-synthesize this lecture into a different perspective:
                        </p>

                        <div class="space-y-2">
                            <button @click="selectedType = 'summary'; generateNote(); showFormatModal = false" 
                                    class="w-full p-3 rounded-xl border border-[#1E283D] hover:border-cyan-400/50 hover:bg-[#131D31] text-left transition flex items-center justify-between group">
                                <div>
                                    <p class="font-semibold text-xs text-white group-hover:text-cyan-300">📚 Comprehensive Study Guide</p>
                                    <p class="text-[10px] text-[#94A3B8]">Detailed concept breakdown, diagrams & model exam answers</p>
                                </div>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-cyan-400"></i>
                            </button>

                            <button @click="selectedType = 'cheat_sheet'; generateNote(); showFormatModal = false" 
                                    class="w-full p-3 rounded-xl border border-[#1E283D] hover:border-amber-400/50 hover:bg-[#131D31] text-left transition flex items-center justify-between group">
                                <div>
                                    <p class="font-semibold text-xs text-white group-hover:text-amber-300">⚡ Rapid Exam Cram Sheet</p>
                                    <p class="text-[10px] text-[#94A3B8]">Formulas, syntax matrix, traps and 5-minute recall summary</p>
                                </div>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
                            </button>

                            <button @click="selectedType = 'quiz_flashcards'; generateNote(); showFormatModal = false" 
                                    class="w-full p-3 rounded-xl border border-[#1E283D] hover:border-purple-400/50 hover:bg-[#131D31] text-left transition flex items-center justify-between group">
                                <div>
                                    <p class="font-semibold text-xs text-white group-hover:text-purple-300">❓ Q&A & Flashcards</p>
                                    <p class="text-[10px] text-[#94A3B8]">Active recall flashcard cards, True/False checks and viva cases</p>
                                </div>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-purple-400"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- More from this Subject -->
            @if($relatedSessions->isNotEmpty())
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-base text-white">
                        More Lectures in {{ $session->subject->subject_name }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($relatedSessions as $related)
                            <a href="{{ route('lectures.show', $related) }}" 
                               class="editorial-card p-4 flex flex-col justify-between group hover:border-amber-400/50 transition">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-[10px] text-[#94A3B8]">
                                        <span>Lec #{{ $related->lecture_number }}</span>
                                        <span>{{ $related->lecture_date->format('M d') }}</span>
                                    </div>
                                    <h4 class="font-display font-semibold text-xs text-white line-clamp-2 group-hover:text-amber-400 transition">
                                        {{ $related->title }}
                                    </h4>
                                </div>
                                <div class="mt-3 pt-2 border-t border-[#1E283D] text-[10px] font-semibold text-cyan-400 flex items-center justify-between">
                                    <span>{{ $related->approvedRecordings->count() }} {{ Str::plural('Part', $related->approvedRecordings->count()) }}</span>
                                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right 4 Columns: Lecture Resources & Companion Files -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="editorial-card p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center">
                            <i data-lucide="paperclip" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-sm text-white">Study Resources</h3>
                            <p class="text-[10px] text-[#94A3B8]">Companion lecture files</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#131D31] text-cyan-300 border border-cyan-500/30">
                        {{ $session->resources->count() }} Files
                    </span>
                </div>

                <div class="divide-y divide-[#1E283D] border-t border-[#1E283D]">
                    @forelse($session->resources as $res)
                        <div class="py-3.5 space-y-1.5 first:pt-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-[#131D31] border border-[#1E283D] text-cyan-400 flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="{{ $res->icon }}" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-xs text-white leading-snug">{{ $res->title }}</h4>
                                        <div class="flex items-center gap-2 text-[10px] text-[#94A3B8] mt-0.5">
                                            <span class="uppercase font-bold text-amber-400 font-mono">{{ $res->type }}</span>
                                            @if($res->file_size)
                                                <span>· {{ $res->file_size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    @if($res->file_path)
                                        <a href="{{ asset('storage/' . $res->file_path) }}" 
                                           download 
                                           class="p-1.5 rounded-lg text-[#94A3B8] hover:text-cyan-400 hover:bg-white/5 transition block"
                                           title="Download File">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                        </a>
                                    @elseif($res->external_url)
                                        <a href="{{ $res->external_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           class="p-1.5 rounded-lg text-[#94A3B8] hover:text-cyan-400 hover:bg-white/5 transition block"
                                           title="Open Link">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-[#94A3B8] space-y-2">
                            <i data-lucide="file-text" class="w-6 h-6 text-gray-600 mx-auto"></i>
                            <p>No extra slide decks or study files attached for this session.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pt-3 border-t border-[#1E283D]">
                    <a href="{{ route('upload.create') }}" class="w-full py-2 rounded-xl bg-[#131D31] hover:bg-[#1A2640] border border-cyan-500/30 text-xs font-semibold text-cyan-300 transition flex items-center justify-center gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Upload Missing Part / Material</span>
                    </a>
                </div>
            </div>

            <!-- Batch & University Info Card -->
            <div class="editorial-card p-6 space-y-3">
                <span class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-wider block">Academic Context</span>
                <div class="text-xs space-y-2 text-[#94A3B8]">
                    <div class="flex justify-between py-1 border-b border-[#1E283D]">
                        <span>Subject Code:</span>
                        <span class="font-semibold text-cyan-400 font-mono">{{ $session->subject->subject_code }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#1E283D]">
                        <span>Academic Batch:</span>
                        <span class="font-semibold text-white">{{ $session->batch->batch_name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#1E283D]">
                        <span>Delivered On:</span>
                        <span class="font-semibold text-white">{{ $session->lecture_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Viewing Permissions:</span>
                        <span class="font-semibold text-emerald-400">All Verified Students</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
function lectureNoteApp() {
    return {
        note: @json($initialNoteData),
        selectedType: '{{ $existingNote ? $existingNote->note_type : "summary" }}',
        studentNotes: @json($existingNote?->student_notes ?? ''),
        loading: false,
        copied: false,
        showAnnotationsEditor: false,
        savingAnnotations: false,
        showFormatModal: false,
        statusIndex: 0,
        statusInterval: null,
        statusMessages: [
            "🔍 Inspecting lecture curriculum & session context...",
            "🧠 Synthesizing foundational core principles & concepts...",
            "💡 Generating high-frequency exam questions & model answers...",
            "📝 Compiling structured revision checklist & markdown...",
        ],

        init() {
            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },

        async generateNote() {
            this.loading = true;
            this.statusIndex = 0;
            this.statusInterval = setInterval(() => {
                this.statusIndex = (this.statusIndex + 1) % this.statusMessages.length;
            }, 1200);

            try {
                const response = await fetch("{{ route('lectures.notes.generate', $session) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        note_type: this.selectedType
                    })
                });

                const data = await response.json();

                if (data.success && data.note) {
                    this.note = data.note;
                    this.studentNotes = data.note.student_notes || '';
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                } else {
                    alert(data.message || 'Error synthesizing lecture note.');
                }
            } catch (err) {
                console.error(err);
                alert('Connection error while generating note. Please try again.');
            } finally {
                clearInterval(this.statusInterval);
                this.loading = false;
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            }
        },

        copyNote() {
            if (!this.note || !this.note.content) return;
            navigator.clipboard.writeText(this.note.content).then(() => {
                this.copied = true;
                setTimeout(() => {
                    this.copied = false;
                }, 2000);
            });
        },

        async saveAnnotations() {
            if (!this.note) return;
            this.savingAnnotations = true;

            try {
                const response = await fetch('/notes/' + this.note.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_notes: this.studentNotes
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.showAnnotationsEditor = false;
                }
            } catch (err) {
                console.error(err);
                alert('Failed to save annotations.');
            } finally {
                this.savingAnnotations = false;
            }
        },

        renderMarkdown(text) {
            if (!text) return '';

            // Escape HTML entities to prevent injection
            let html = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Code blocks ```code```
            html = html.replace(/```([a-z]*)\n([\s\S]*?)```/g, (match, lang, code) => {
                return `<pre class="p-4 my-3 rounded-2xl bg-[#070913] border border-[#1E283D] font-mono text-xs text-cyan-300 overflow-x-auto shadow-inner leading-relaxed"><code>${code.trim()}</code></pre>`;
            });

            // Blockquotes > text
            html = html.replace(/^&gt;\s+(.+)$/gm, '<blockquote class="p-3.5 my-3 bg-[#0D1426] border-l-4 border-amber-400 text-slate-200 rounded-r-xl italic">$1</blockquote>');

            // Headings
            html = html.replace(/^###\s+(.+)$/gm, '<h3 class="font-display font-bold text-base text-cyan-300 mt-5 mb-2 pb-1 border-b border-[#1E283D]">$1</h3>');
            html = html.replace(/^##\s+(.+)$/gm, '<h2 class="font-display font-bold text-lg text-white mt-6 mb-2.5">$1</h2>');
            html = html.replace(/^#\s+(.+)$/gm, '<h1 class="font-display font-extrabold text-xl text-amber-400 mt-4 mb-3">$1</h1>');

            // Horizontal rules ---
            html = html.replace(/^---$/gm, '<hr class="border-[#1E283D] my-5">');

            // Bold & Italics
            html = html.replace(/\*\*(.+?)\*\*/g, '<strong class="text-white font-bold">$1</strong>');
            html = html.replace(/\*(.+?)\*/g, '<em class="text-slate-300 italic">$1</em>');

            // Checkbox lists - [ ] or - [x]
            html = html.replace(/^- \[ \]\s+(.+)$/gm, '<div class="flex items-center gap-2 py-1 text-xs text-slate-300"><span class="w-4 h-4 rounded border border-slate-600 bg-[#070913] flex items-center justify-center"></span><span>$1</span></div>');
            html = html.replace(/^- \[x\]\s+(.+)$/gm, '<div class="flex items-center gap-2 py-1 text-xs text-emerald-300"><span class="w-4 h-4 rounded bg-emerald-500 text-black flex items-center justify-center font-bold text-[10px]">✓</span><span>$1</span></div>');

            // Bullet lists - item
            html = html.replace(/^- (.+)$/gm, '<li class="flex items-start gap-2 py-0.5"><span class="text-cyan-400 font-bold shrink-0">·</span><span>$1</span></li>');

            // LaTeX math display $$...$$ and inline $...$
            html = html.replace(/\$\$([\s\S]+?)\$\$/g, '<div class="py-2.5 px-4 my-2.5 rounded-xl bg-[#0D1426] border border-cyan-500/20 text-center font-mono text-xs text-cyan-300 tracking-wide">$1</div>');
            html = html.replace(/\$([^\$\n]+?)\$/g, '<code class="px-1.5 py-0.5 rounded bg-cyan-950/60 text-cyan-300 font-mono text-[11px] border border-cyan-500/20">$1</code>');

            // Convert consecutive newlines to paragraphs
            return html
                .split('\n\n')
                .map(p => {
                    p = p.trim();
                    if (!p) return '';
                    if (p.startsWith('<h') || p.startsWith('<pre') || p.startsWith('<blockquote') || p.startsWith('<hr') || p.startsWith('<div')) {
                        return p;
                    }
                    return `<p class="leading-relaxed mb-2.5">${p}</p>`;
                })
                .join('');
        }
    }
}
</script>
@endpush
@endsection
