@extends('layouts.app')

@section('content')
<div class="space-y-16 py-4">

    <!-- Hero Section: Featuring the Uploaded Cyber-Tech Image -->
    <section class="relative overflow-hidden rounded-3xl border border-[#1E283D] shadow-[0_20px_50px_rgba(0,0,0,0.8)] min-h-[540px] flex items-center">
        
        <!-- Background Image from user -->
        <div class="absolute inset-0 bg-cover bg-center md:bg-right transition-transform duration-700 ease-out"
             style="background-image: url('{{ asset('images/hero.jpg') }}');">
        </div>

        <!-- Atmospheric Dark Gradients to ensure text readability on the left while preserving the laptop & coffee cup on the right -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#070913] via-[#070913]/90 md:via-[#070913]/85 md:via-55% to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#070913] via-transparent to-[#070913]/40"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Hero Content (Left-aligned, stylish & modern) -->
        <div class="relative z-10 max-w-2xl p-8 sm:p-12 lg:p-16 space-y-6">
            
            <!-- Neon Glowing Pill Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#0C1222]/80 border border-amber-500/30 backdrop-blur-md shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_#F59E0B] animate-pulse"></span>
                <span class="text-xs font-semibold text-amber-200 tracking-wide">UniReplay · University Lecture Hub</span>
            </div>

            <!-- Grand Headline with Ambient Gradient -->
            <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.06] text-white">
                Learn. Revisit. <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-cyan-400 drop-shadow-[0_0_25px_rgba(245,158,11,0.35)]">Replay.</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal max-w-xl">
                The centralized university lecture repository where verified students discover, stream, and share class recordings and structured study materials. Never miss a concept again.
            </p>

            <!-- Action Buttons -->
            <div class="pt-2 flex flex-wrap items-center gap-4">
                @auth
                    @if(auth()->user()->canAccessLectures())
                        <a href="{{ route('lectures.index') }}" 
                           class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-white font-semibold text-sm transition shadow-[0_0_25px_rgba(245,158,11,0.4)] hover:shadow-[0_0_35px_rgba(245,158,11,0.6)] flex items-center gap-2">
                            <i data-lucide="play" class="w-4 h-4 fill-current"></i>
                            <span>Browse Lecture Library</span>
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 backdrop-blur-md text-white font-semibold text-sm border border-white/20 transition flex items-center gap-2">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-300"></i>
                            <span>Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('verification.notice') }}" 
                           class="px-6 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm transition shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                            Check Verification Status
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" 
                       class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-white font-semibold text-sm transition shadow-[0_0_25px_rgba(245,158,11,0.4)] hover:shadow-[0_0_35px_rgba(245,158,11,0.6)] flex items-center gap-2">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                        <span>Register as Student</span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 backdrop-blur-md text-white font-semibold text-sm border border-white/20 transition flex items-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4 text-cyan-300"></i>
                        <span>Student / Admin Sign In</span>
                    </a>
                @endauth
            </div>

            <!-- Quick Demo Shortcut Pill -->
            <div class="pt-4 border-t border-white/15 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                <span class="text-amber-300 font-semibold flex items-center gap-1">
                    <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i>
                    Instant Demo Login:
                </span>
                <a href="{{ route('demo.quick-login', 'admin') }}" class="underline hover:text-white transition">Admin (Dr. Sarah Chen)</a>
                <span>·</span>
                <a href="{{ route('demo.quick-login', 'student') }}" class="underline hover:text-white transition">Student (Alex Rivera)</a>
                <span>·</span>
                <a href="{{ route('demo.quick-login', 'pending') }}" class="underline hover:text-white transition">Pending (Marcus)</a>
            </div>
        </div>

        <!-- Subtle Floating Bottom-Right Cyber Tag -->
        <div class="hidden lg:flex absolute bottom-6 right-6 z-10 items-center gap-2 px-3 py-1.5 rounded-xl bg-[#070913]/80 backdrop-blur-md border border-cyan-500/30 text-xs text-cyan-200">
            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_6px_#38BDF8]"></span>
            <span class="font-mono text-[11px]">ARCHIVE // 2026 BATCH ENGINE</span>
        </div>
    </section>

    <!-- Platform Vital Statistics (Matched with glowing accents) -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider">Published Recordings</span>
                <div class="w-9 h-9 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center shadow-[0_0_12px_rgba(56,189,248,0.2)]">
                    <i data-lucide="film" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-white">{{ $stats['recordings_count'] }}</span>
                <p class="text-xs text-[#94A3B8] mt-1">Verified video parts online</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider">Verified Students</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-white">{{ $stats['students_count'] }}</span>
                <p class="text-xs text-[#94A3B8] mt-1">Active verified learners</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider">Subjects Covered</span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center shadow-[0_0_12px_rgba(245,158,11,0.2)]">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-white">{{ $stats['subjects_count'] }}</span>
                <p class="text-xs text-[#94A3B8] mt-1">Academic curriculum modules</p>
            </div>
        </div>

        <div class="editorial-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#94A3B8]">
                <span class="text-xs font-semibold uppercase tracking-wider">Batches Supported</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center shadow-[0_0_12px_rgba(99,102,241,0.2)]">
                    <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-display font-bold text-3xl sm:text-4xl text-white">{{ $stats['batches_count'] }}</span>
                <p class="text-xs text-[#94A3B8] mt-1">Cross-batch open access</p>
            </div>
        </div>
    </section>

    <!-- Editorial Feature Flow: The UniReplay Workflow -->
    <section class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400">How It Works</span>
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-white mt-1">Quality Guaranteed by Design</h2>
            </div>
            <p class="text-xs sm:text-sm text-[#94A3B8] max-w-md">
                Every lecture recording is uploaded by verified university students and moderated by assigned batch faculty before being published.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="editorial-card p-6 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-[#131D31] text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-display font-bold text-base shadow-[0_0_10px_rgba(56,189,248,0.2)]">
                    01
                </div>
                <h3 class="font-display font-bold text-lg text-white">Student Verification</h3>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Students register with their university identity and batch. Assigned administrators approve profiles, ensuring a safe academic space.
                </p>
            </div>

            <div class="editorial-card p-6 space-y-4 border-l-4 border-l-amber-500">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center font-display font-bold text-base shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                    02
                </div>
                <h3 class="font-display font-bold text-lg text-white">Multi-Part Upload & Review</h3>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Upload sessions with multiple recording parts and study attachments (PDFs, ZIPs). Batch-assigned admins review and approve each part.
                </p>
            </div>

            <div class="editorial-card p-6 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-[#131D31] text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-display font-bold text-base shadow-[0_0_10px_rgba(56,189,248,0.2)]">
                    03
                </div>
                <h3 class="font-display font-bold text-lg text-white">Cross-Batch Library</h3>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Once published, all verified students can search, watch, bookmark, and download resources across all batches without silos.
                </p>
            </div>
        </div>
    </section>

    <!-- Recent Lecture Sessions Grid -->
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-cyan-400">Lecture Catalog</span>
                <h2 class="font-display font-bold text-2xl text-white mt-1">Recently Published Lectures</h2>
            </div>
            @auth
                @if(auth()->user()->canAccessLectures())
                    <a href="{{ route('lectures.index') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
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
                        <div class="relative aspect-video bg-[#04060A] flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D1322] via-[#0D1322]/40 to-transparent"></div>
                            
                            <!-- Topic Code Badge -->
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500 text-black shadow-sm font-mono">
                                    {{ $session->subject->subject_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-black/70 text-white backdrop-blur-sm border border-white/10">
                                    Lec #{{ $session->lecture_number }}
                                </span>
                            </div>

                            <!-- Parts indicator -->
                            <div class="absolute top-3 right-3 z-10">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500 text-black font-bold shadow-md flex items-center gap-1">
                                    <i data-lucide="layers" class="w-3 h-3"></i>
                                    <span>{{ $session->approvedRecordings->count() }} {{ Str::plural('Part', $session->approvedRecordings->count()) }}</span>
                                </span>
                            </div>

                            <!-- Big Play Button Overlay -->
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_20px_rgba(245,158,11,0.5)] transform group-hover:scale-110 transition duration-150 z-10">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5 text-white"></i>
                            </div>

                            <!-- Bottom bar inside thumbnail -->
                            <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-gray-200 z-10">
                                <span class="truncate max-w-[180px]">{{ $session->batch->batch_name }}</span>
                                <span>{{ $session->lecture_date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2">
                            <p class="text-[11px] font-semibold text-cyan-400 tracking-wide uppercase font-mono">{{ $session->subject->subject_name }}</p>
                            <h3 class="font-display font-bold text-base text-white line-clamp-2 leading-snug group-hover:text-amber-400 transition">
                                {{ $session->title }}
                            </h3>
                            <p class="text-xs text-[#94A3B8] line-clamp-2 leading-relaxed">
                                {{ $session->description ?? 'No extra summary provided.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-5 py-3.5 border-t border-[#1E283D] bg-[#0A0E1A] flex items-center justify-between">
                        <span class="text-[11px] text-[#94A3B8] flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-cyan-400"></i>
                            <span>{{ $session->lecture_date->format('D, M j') }}</span>
                        </span>

                        @auth
                            @if(auth()->user()->canAccessLectures())
                                <a href="{{ route('lectures.show', $session) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-amber-400 group-hover:text-amber-300">
                                    <span>Watch</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @else
                                <a href="{{ route('verification.notice') }}" class="text-xs font-semibold text-amber-500">
                                    Verification Needed
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-cyan-400 hover:text-cyan-300">
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
