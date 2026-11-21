<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Cockpit' }} — UniReplay Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#070913] text-[#F8FAFC] antialiased">

    <!-- Hackathon Demo Switcher -->
    @include('components.demo-banner')

    <!-- Admin Topbar -->
    <header class="bg-[#070913]/95 text-white border-b border-[#1E283D] sticky top-8 z-40 shadow-sm backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Admin Brand & Batch Pill -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-[0_0_12px_rgba(245,158,11,0.4)]">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-display font-bold text-base tracking-tight leading-none text-white">UniReplay</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 uppercase tracking-widest">Admin</span>
                            </div>
                            <span class="text-[10px] text-[#94A3B8]">Moderation Cockpit</span>
                        </div>
                    </a>

                    <!-- Moderator Assignment Badge -->
                    @if(auth()->user()->batch)
                        <div class="hidden md:flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0E1626] border border-cyan-500/30 text-xs">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_6px_#38BDF8]"></span>
                            <span class="text-gray-400">Moderating:</span>
                            <span class="text-cyan-300 font-medium">{{ auth()->user()->batch->batch_name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Admin Navigation Tabs -->
                <nav class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm' : 'text-[#94A3B8] hover:text-white hover:bg-white/5' }}">
                        Overview
                    </a>
                    <a href="{{ route('admin.students.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('admin.students.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm' : 'text-[#94A3B8] hover:text-white hover:bg-white/5' }}">
                        <span>Students</span>
                        @php
                            $pendingUsers = \App\Models\User::where('role', 'student')->where('status', 'pending')->count();
                        @endphp
                        @if($pendingUsers > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-400 text-black font-bold">{{ $pendingUsers }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.recordings.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('admin.recordings.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm' : 'text-[#94A3B8] hover:text-white hover:bg-white/5' }}">
                        <span>Recordings</span>
                        @php
                            $pendingRecs = \App\Models\Recording::where('status', 'pending')->count();
                        @endphp
                        @if($pendingRecs > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-cyan-400 text-black font-bold">{{ $pendingRecs }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs text-cyan-400 hover:text-cyan-300 hover:bg-cyan-500/10 transition flex items-center gap-1 border border-cyan-500/20">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Student View</span>
                    </a>
                </nav>

                <!-- Admin Profile -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 text-right">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-black flex items-center justify-center font-bold text-xs">
                            {{ auth()->user()->initials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-amber-300 leading-tight">Administrator</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-400 hover:bg-white/5 transition" title="Sign Out">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-[#092B20] border border-[#10B981]/40 p-3.5 text-emerald-200 flex items-center justify-between text-sm shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-[#10B981] text-black flex items-center justify-center shrink-0 shadow-[0_0_8px_#10B981]">
                        <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 rounded-xl bg-[#2D1B00] border border-amber-500/40 p-3.5 text-amber-200 flex items-center justify-between text-sm shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-amber-500 text-black flex items-center justify-center shrink-0 shadow-[0_0_8px_#F59E0B]">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 stroke-[3]"></i>
                    </div>
                    <span class="font-medium">{{ session('warning') }}</span>
                </div>
                <button @click="show = false" class="text-amber-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
