@extends('layouts.app')

@section('content')
<div class="space-y-16 py-4">

    <!-- Hero Section: Editorial Neo-Tech -->
    <section class="relative overflow-hidden rounded-3xl bg-[#17171D] text-white p-8 sm:p-12 lg:p-16 border border-[#24242D] shadow-2xl">
        <!-- Subtle architectural grid / ambient glow -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#7657F6]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-[#B7A7FF]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-3xl space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#24242D] border border-white/10 text-xs text-gray-300">
                <span class="w-2 h-2 rounded-full bg-[#7657F6]"></span>
                <span class="font-medium">UniReplay · Centralized University Lecture Hub</span>
            </div>

            <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.08] text-white">
                Learn. Revisit. <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#B7A7FF] to-[#7657F6]">Replay.</span>
            </h1>

            <p class="text-base sm:text-lg text-gray-300 leading-relaxed font-normal max-w-2xl">
                The centralized university lecture repository where verified students discover, stream, and share class recordings and structured study materials. Never miss a concept again.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-4">
                @auth
                    @if(auth()->user()->canAccessLectures())
                        <a href="{{ route('lectures.index') }}" 
                           class="px-6 py-3.5 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white font-semibold text-sm transition shadow-lg hover:shadow-xl flex items-center gap-2">
                            <i data-lucide="play" class="w-4 h-4 fill-current"></i>
                            <span>Browse Lecture Library</span>
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-3.5 rounded-xl bg-[#24242D] hover:bg-[#2e2e3a] text-white font-semibold text-sm border border-white/10 transition flex items-center gap-2">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('verification.notice') }}" 
                           class="px-6 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm transition">
                            Check Verification Status
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" 
                       class="px-6 py-3.5 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white font-semibold text-sm transition shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                        <span>Register as Student</span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-6 py-3.5 rounded-xl bg-[#24242D] hover:bg-[#2e2e3a] text-white font-semibold text-sm border border-white/10 transition flex items-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span>Student / Admin Sign In</span>
                    </a>
                @endauth
            </div>

            <!-- Quick Demo Shortcut Pill -->
            <div class="pt-4 border-t border-white/10 flex items-center gap-3 text-xs text-gray-400">
                <span class="text-white font-medium flex items-center gap-1">
                    <i data-lucide="zap" class="w-3.5 h-3.5 text-[#B7A7FF]"></i>
                    Instant Demo Login:
                </span>
                <a href="{{ route('demo.quick-login', 'admin') }}" class="underline hover:text-white transition">Admin (Dr. Sarah Chen)</a>
                <span>·</span>
                <a href="{{ route('demo.quick-login', 'student') }}" class="underline hover:text-white transition">Student (Alex Rivera)</a>
                <span>·</span>
                <a href="{{ route('demo.quick-login', 'pending') }}" class="underline hover:text-white transition">Pending (Marcus)</a>
            </div>
        </div>
    </section>

    <!-- Platform Vital Statistics -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider">Published Recordings</span>
                <div class="w-8 h-8 rounded-lg bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center">
                    <i data-lucide="film" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-[#17171D]">{{ $stats['recordings_count'] }}</span>
                <p class="text-xs text-[#777681] mt-1">Verified video parts online</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider">Verified Students</span>
                <div class="w-8 h-8 rounded-lg bg-[#EBF8F4] text-[#27B58A] flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-[#17171D]">{{ $stats['students_count'] }}</span>
                <p class="text-xs text-[#777681] mt-1">Active verified learners</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider">Subjects Covered</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-[#17171D]">{{ $stats['subjects_count'] }}</span>
                <p class="text-xs text-[#777681] mt-1">Academic curriculum modules</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#777681]">
                <span class="text-xs font-semibold uppercase tracking-wider">Batches Supported</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-[#17171D]">{{ $stats['batches_count'] }}</span>
                <p class="text-xs text-[#777681] mt-1">Cross-batch open access</p>
            </div>
        </div>
    </section>

    <!-- Editorial Feature Flow: The UniReplay Workflow -->
    <section class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#7657F6]">How It Works</span>
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-[#17171D] mt-1">Quality Guaranteed by Design</h2>
            </div>
            <p class="text-xs sm:text-sm text-[#777681] max-w-md">
                Every lecture recording is uploaded by verified university students and moderated by assigned batch faculty before being published.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="editorial-card p-6 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-[#17171D] text-white flex items-center justify-center font-display font-bold text-base">
                    01
                </div>
                <h3 class="font-display font-bold text-lg text-[#17171D]">Student Verification</h3>
                <p class="text-xs text-[#777681] leading-relaxed">
                    Students register with their university identity and batch. Assigned administrators approve profiles, ensuring a safe academic space.
                </p>
            </div>

            <div class="editorial-card p-6 space-y-4 border-l-4 border-l-[#7657F6]">
                <div class="w-10 h-10 rounded-xl bg-[#7657F6] text-white flex items-center justify-center font-display font-bold text-base">
                    02
                </div>
                <h3 class="font-display font-bold text-lg text-[#17171D]">Multi-Part Upload & Review</h3>
                <p class="text-xs text-[#777681] leading-relaxed">
                    Upload sessions with multiple recording parts and study attachments (PDFs, ZIPs). Batch-assigned admins review and approve each part.
                </p>
            </div>

            <div class="editorial-card p-6 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-[#17171D] text-white flex items-center justify-center font-display font-bold text-base">
                    03
                </div>
                <h3 class="font-display font-bold text-lg text-[#17171D]">Cross-Batch Library</h3>
                <p class="text-xs text-[#777681] leading-relaxed">
                    Once published, all verified students can search, watch, bookmark, and download resources across all batches without silos.
                </p>
            </div>
        </div>
    </section>

    <!-- Recent Lecture Sessions Grid -->
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#7657F6]">Lecture Catalog</span>
                <h2 class="font-display font-bold text-2xl text-[#17171D] mt-1">Recently Published Lectures</h2>
            </div>
            @auth
                @if(auth()->user()->canAccessLectures())
                    <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-[#7657F6] hover:underline flex items-center gap-1">
                        <span>View All ({{ $stats['recordings_count'] }})</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            @endauth
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredSessions as $session)
                <div class="editorial-card overflow-hidden flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail Area -->
                        <div class="relative aspect-video bg-[#17171D] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            
                            <!-- Topic Code Badge -->
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/90 text-[#17171D] backdrop-blur-sm">
                                    {{ $session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-black/60 text-white backdrop-blur-sm border border-white/10">
                                    Lec #{{ $session->lecture_number }}
                                </span>
                            </div>

                            <!-- Parts indicator -->
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#7657F6] text-white shadow-sm flex items-center gap-1">
                                    <i data-lucide="layers" class="w-3 h-3"></i>
                                    <span>{{ $session->approvedRecordings->count() }} {{ Str::plural('Part', $session->approvedRecordings->count()) }}</span>
                                </span>
                            </div>

                            <!-- Big Play Button Overlay -->
                            <div class="w-12 h-12 rounded-full bg-white/90 text-[#17171D] flex items-center justify-center shadow-lg transform group-hover:scale-110 transition duration-150">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5 text-[#17171D]"></i>
                            </div>

                            <!-- Bottom bar inside thumbnail -->
                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200">
                                <span class="truncate max-w-[180px]">{{ $session->batch->batch_name }}</span>
                                <span>{{ $session->lecture_date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <p class="text-[11px] font-semibold text-[#7657F6] tracking-wide uppercase">{{ $session->subject->subject_name }}</p>
                            <h3 class="font-display font-bold text-base text-[#17171D] line-clamp-2 leading-snug group-hover:text-[#7657F6] transition">
                                {{ $session->title }}
                            </h3>
                            <p class="text-xs text-[#777681] line-clamp-2 leading-relaxed">
                                {{ $session->description ?? 'No extra summary provided.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-5 py-3.5 border-t border-[#E5E2DC] bg-[#FAF8F5] flex items-center justify-between">
                        <span class="text-[11px] text-[#777681] flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>{{ $session->lecture_date->format('D, M j') }}</span>
                        </span>

                        @auth
                            @if(auth()->user()->canAccessLectures())
                                <a href="{{ route('lectures.show', $session) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#17171D] group-hover:text-[#7657F6]">
                                    <span>Watch</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @else
                                <a href="{{ route('verification.notice') }}" class="text-xs font-semibold text-amber-600">
                                    Verification Needed
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#7657F6]">
                                <span>Sign In to Watch</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection
