@extends('layouts.app')

@section('content')
<div class="space-y-8 py-2">

    <!-- Top Navigation Breadcrumbs & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('lectures.index', ['subject_id' => $session->subject_id]) }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#777681] hover:text-[#17171D] transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to {{ $session->subject->subject_code }} Lectures</span>
        </a>

        <!-- Bookmark Button -->
        <form action="{{ route('bookmarks.toggle', $session) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#E5E2DC] bg-white hover:bg-[#FAF8F5] text-xs font-semibold text-[#17171D] transition shadow-sm">
                <i data-lucide="bookmark" class="w-4 h-4 {{ $isBookmarked ? 'fill-[#7657F6] text-[#7657F6]' : 'text-[#777681]' }}"></i>
                <span>{{ $isBookmarked ? 'Saved in Bookmarks' : 'Bookmark Lecture' }}</span>
            </button>
        </form>
    </div>

    <!-- Main Player Theater Area (Editorial Dark Surface for Media) -->
    <div class="rounded-3xl bg-[#17171D] text-white overflow-hidden border border-[#24242D] shadow-2xl">
        <div class="p-4 sm:p-6 lg:p-8 space-y-4">
            
            <!-- Video Player Element -->
            <div class="relative aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-inner flex items-center justify-center">
                @if($activeRecording)
                    <video id="lecturePlayer"
                           controls 
                           preload="metadata"
                           class="w-full h-full object-contain"
                           controlsList="nodownload">
                        <source src="{{ route('recordings.stream', $activeRecording) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
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
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">Session Parts:</span>
                    @foreach($approvedRecordings as $rec)
                        @php
                            $isActive = $activeRecording && $activeRecording->id === $rec->id;
                        @endphp
                        <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $rec->id]) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $isActive ? 'bg-[#7657F6] text-white shadow-md ring-2 ring-[#7657F6]/50' : 'bg-[#24242D] hover:bg-[#2e2e3a] text-gray-300 border border-white/10' }}">
                            <i data-lucide="{{ $isActive ? 'play' : 'video' }}" class="w-3.5 h-3.5 {{ $isActive ? 'fill-current' : '' }}"></i>
                            <span>Part {{ $rec->part_number }}</span>
                            @if($rec->duration)
                                <span class="opacity-70 text-[10px]">({{ $rec->duration }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Previous / Next Part Buttons -->
                @if($activeRecording)
                    @php
                        $currentIndex = $approvedRecordings->search(fn($r) => $r->id === $activeRecording->id);
                        $prevRec = $currentIndex > 0 ? $approvedRecordings->get($currentIndex - 1) : null;
                        $nextRec = $currentIndex < ($approvedRecordings->count() - 1) ? $approvedRecordings->get($currentIndex + 1) : null;
                    @endphp
                    <div class="flex items-center gap-2">
                        @if($prevRec)
                            <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $prevRec->id]) }}" 
                               class="px-3 py-1.5 rounded-xl bg-[#24242D] hover:bg-[#2e2e3a] text-gray-300 text-xs font-medium border border-white/10 transition flex items-center gap-1">
                                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                <span>Part {{ $prevRec->part_number }}</span>
                            </a>
                        @endif

                        @if($nextRec)
                            <a href="{{ route('lectures.show', ['session' => $session->id, 'part' => $nextRec->id]) }}" 
                               class="px-3 py-1.5 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                <span>Next: Part {{ $nextRec->part_number }}</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </div>

    <!-- 2-Column Info & Resources Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 8 Columns: Lecture Details & Overview -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="editorial-card p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-[#F0EDFF] text-[#7657F6]">
                        {{ $session->subject->subject_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-[#FAF8F5] text-[#17171D] border border-[#E5E2DC]">
                        Lecture Session #{{ $session->lecture_number }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-[#FAF8F5] text-[#777681] border border-[#E5E2DC]">
                        {{ $session->batch->batch_name }}
                    </span>
                </div>

                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[#17171D] tracking-tight leading-snug">
                    {{ $session->title }}
                </h1>

                <!-- Metadata Row -->
                <div class="flex flex-wrap items-center gap-4 text-xs text-[#777681] pt-2 pb-4 border-b border-[#E5E2DC]">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-[#7657F6]"></i>
                        <span>Date: <strong class="text-[#17171D]">{{ $session->lecture_date->format('F d, Y') }}</strong></span>
                    </span>
                    <span>·</span>
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="layers" class="w-4 h-4 text-[#7657F6]"></i>
                        <span>Total Parts: <strong class="text-[#17171D]">{{ $session->approvedRecordings->count() }}</strong></span>
                    </span>
                    <span>·</span>
                    @if($activeRecording && $activeRecording->uploader)
                        <span class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[9px] text-white"
                                 style="background-color: {{ $activeRecording->uploader->avatar_color ?? '#7657F6' }}">
                                {{ $activeRecording->uploader->initials }}
                            </div>
                            <span>Uploaded by: <strong class="text-[#17171D]">{{ $activeRecording->uploader->name }}</strong></span>
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h3 class="font-display font-bold text-sm text-[#17171D]">Lecture Syllabus & Description</h3>
                    <p class="text-xs sm:text-sm text-[#777681] leading-relaxed whitespace-pre-line">
                        {{ $session->description ?? 'No detailed description provided for this session.' }}
                    </p>
                </div>

                <!-- Academic Integrity & Verification Notice -->
                <div class="mt-6 p-4 rounded-xl bg-[#FAF8F5] border border-[#E5E2DC] flex items-start gap-3 text-xs text-[#777681]">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#27B58A] shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-semibold text-[#17171D]">Faculty Verified Recording</span>
                        <p class="mt-0.5">This recording was reviewed and approved for publication by university administration under the UniReplay academic honor code.</p>
                    </div>
                </div>
            </div>

            <!-- More from this Subject -->
            @if($relatedSessions->isNotEmpty())
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-base text-[#17171D]">
                        More Lectures in {{ $session->subject->subject_name }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($relatedSessions as $related)
                            <a href="{{ route('lectures.show', $related) }}" 
                               class="editorial-card p-4 flex flex-col justify-between group hover:border-[#7657F6]/50 transition">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-[10px] text-[#777681]">
                                        <span>Lec #{{ $related->lecture_number }}</span>
                                        <span>{{ $related->lecture_date->format('M d') }}</span>
                                    </div>
                                    <h4 class="font-display font-semibold text-xs text-[#17171D] line-clamp-2 group-hover:text-[#7657F6] transition">
                                        {{ $related->title }}
                                    </h4>
                                </div>
                                <div class="mt-3 pt-2 border-t border-[#E5E2DC] text-[10px] font-semibold text-[#7657F6] flex items-center justify-between">
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
                        <div class="w-8 h-8 rounded-lg bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center">
                            <i data-lucide="paperclip" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-sm text-[#17171D]">Study Resources</h3>
                            <p class="text-[10px] text-[#777681]">Companion lecture files</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FAF8F5] text-[#17171D] border border-[#E5E2DC]">
                        {{ $session->resources->count() }} Files
                    </span>
                </div>

                <div class="divide-y divide-[#E5E2DC] border-t border-[#E5E2DC]">
                    @forelse($session->resources as $res)
                        <div class="py-3.5 space-y-1.5 first:pt-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-[#FAF8F5] border border-[#E5E2DC] text-[#7657F6] flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="{{ $res->icon }}" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-xs text-[#17171D] leading-snug">{{ $res->title }}</h4>
                                        <div class="flex items-center gap-2 text-[10px] text-[#777681] mt-0.5">
                                            <span class="uppercase font-bold text-[#7657F6]">{{ $res->type }}</span>
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
                                           class="p-1.5 rounded-lg text-[#777681] hover:text-[#7657F6] hover:bg-[#FAF8F5] transition block"
                                           title="Download File">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                        </a>
                                    @elseif($res->external_url)
                                        <a href="{{ $res->external_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           class="p-1.5 rounded-lg text-[#777681] hover:text-[#7657F6] hover:bg-[#FAF8F5] transition block"
                                           title="Open Link">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-[#777681] space-y-2">
                            <i data-lucide="file-text" class="w-6 h-6 text-gray-300 mx-auto"></i>
                            <p>No extra slide decks or study files attached for this session.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pt-3 border-t border-[#E5E2DC]">
                    <a href="{{ route('upload.create') }}" class="w-full py-2 rounded-xl bg-[#FAF8F5] hover:bg-white border border-[#E5E2DC] text-xs font-semibold text-[#17171D] transition flex items-center justify-center gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5 text-[#7657F6]"></i>
                        <span>Upload Missing Part / Material</span>
                    </a>
                </div>
            </div>

            <!-- Batch & University Info Card -->
            <div class="editorial-card p-6 space-y-3">
                <span class="text-[10px] font-bold text-[#777681] uppercase tracking-wider block">Academic Context</span>
                <div class="text-xs space-y-2 text-[#777681]">
                    <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                        <span>Subject Code:</span>
                        <span class="font-semibold text-[#17171D]">{{ $session->subject->subject_code }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                        <span>Academic Batch:</span>
                        <span class="font-semibold text-[#17171D]">{{ $session->batch->batch_name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#E5E2DC]">
                        <span>Delivered On:</span>
                        <span class="font-semibold text-[#17171D]">{{ $session->lecture_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Viewing Permissions:</span>
                        <span class="font-semibold text-[#27B58A]">All Verified Students</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
