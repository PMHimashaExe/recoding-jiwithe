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
<body class="min-h-full flex flex-col bg-[#F5F3EF] text-[#17171D] antialiased">

    <!-- Hackathon Demo Switcher -->
    @include('components.demo-banner')

    <!-- Admin Topbar -->
    <header class="bg-[#17171D] text-white border-b border-[#24242D] sticky top-8 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Admin Brand & Batch Pill -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#7657F6] text-white flex items-center justify-center shadow-sm">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-display font-bold text-base tracking-tight leading-none text-white">UniReplay</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-[#7657F6]/30 text-[#B7A7FF] border border-[#7657F6]/40 uppercase tracking-widest">Admin</span>
                            </div>
                            <span class="text-[10px] text-gray-400">Moderation Cockpit</span>
                        </div>
                    </a>

                    <!-- Moderator Assignment Badge -->
                    @if(auth()->user()->batch)
                        <div class="hidden md:flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#24242D] border border-gray-700/60 text-xs">
                            <span class="w-2 h-2 rounded-full bg-[#27B58A]"></span>
                            <span class="text-gray-400">Moderating:</span>
                            <span class="text-white font-medium">{{ auth()->user()->batch->batch_name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Admin Navigation Tabs -->
                <nav class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#7657F6] text-white' : 'text-gray-300 hover:text-white hover:bg-[#24242D]' }}">
                        Overview
                    </a>
                    <a href="{{ route('admin.students.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('admin.students.*') ? 'bg-[#7657F6] text-white' : 'text-gray-300 hover:text-white hover:bg-[#24242D]' }}">
                        <span>Students</span>
                        @php
                            $pendingUsers = \App\Models\User::where('role', 'student')->where('status', 'pending')->count();
                        @endphp
                        @if($pendingUsers > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-400 text-black font-bold">{{ $pendingUsers }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.recordings.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('admin.recordings.*') ? 'bg-[#7657F6] text-white' : 'text-gray-300 hover:text-white hover:bg-[#24242D]' }}">
                        <span>Recordings</span>
                        @php
                            $pendingRecs = \App\Models\Recording::where('status', 'pending')->count();
                        @endphp
                        @if($pendingRecs > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-[#7657F6] text-white font-bold">{{ $pendingRecs }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-[#24242D] transition flex items-center gap-1">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Student View</span>
                    </a>
                </nav>

                <!-- Admin Profile -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 text-right">
                        <div class="w-8 h-8 rounded-lg bg-[#7657F6] flex items-center justify-center font-bold text-xs text-white">
                            {{ auth()->user()->initials }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400 leading-tight">Administrator</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-[#24242D] transition" title="Sign Out">
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
            <div class="mb-4 rounded-xl bg-[#EBF8F4] border border-[#27B58A]/30 p-3.5 text-[#17171D] flex items-center justify-between text-sm shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-[#27B58A] text-white flex items-center justify-center shrink-0">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 p-3.5 text-amber-900 flex items-center justify-between text-sm shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-medium">{{ session('warning') }}</span>
                </div>
                <button @click="show = false" class="text-amber-400 hover:text-amber-600">
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
