<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'UniReplay' }} — Learn. Revisit. Replay.</title>

    <!-- Google Fonts: Sora & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#F5F3EF] text-[#17171D] selection:bg-[#7657F6] selection:text-white antialiased">

    <!-- Hackathon Demo Switcher -->
    @include('components.demo-banner')

    <!-- Top Navigation Bar -->
    <header class="bg-[#F5F3EF]/90 backdrop-blur-md border-b border-[#E5E2DC] sticky top-8 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ auth()->check() && auth()->user()->canAccessLectures() ? route('dashboard') : route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-[#17171D] flex items-center justify-center text-white shadow-sm group-hover:bg-[#7657F6] transition-colors">
                            <i data-lucide="play" class="w-4 h-4 fill-current ml-0.5"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-display font-bold text-lg tracking-tight text-[#17171D] leading-none">UniReplay</span>
                            <span class="text-[10px] text-[#777681] font-medium tracking-wide">Learn · Revisit · Replay</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                        @auth
                            @if(auth()->user()->canAccessLectures())
                                <a href="{{ route('dashboard') }}" 
                                   class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-white text-[#7657F6] shadow-sm font-semibold' : 'text-[#777681] hover:text-[#17171D] hover:bg-white/60' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('lectures.index') }}" 
                                   class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('lectures.*') ? 'bg-white text-[#7657F6] shadow-sm font-semibold' : 'text-[#777681] hover:text-[#17171D] hover:bg-white/60' }}">
                                    Lecture Library
                                </a>
                                <a href="{{ route('my-uploads') }}" 
                                   class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('my-uploads') ? 'bg-white text-[#7657F6] shadow-sm font-semibold' : 'text-[#777681] hover:text-[#17171D] hover:bg-white/60' }}">
                                    My Uploads
                                </a>
                                <a href="{{ route('bookmarks.index') }}" 
                                   class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('bookmarks.*') ? 'bg-white text-[#7657F6] shadow-sm font-semibold' : 'text-[#777681] hover:text-[#17171D] hover:bg-white/60' }}">
                                    Saved
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="px-3 py-1.5 rounded-lg text-white bg-[#17171D] hover:bg-[#24242D] transition shadow-sm font-semibold flex items-center gap-1.5 ml-2">
                                    <i data-lucide="shield" class="w-3.5 h-3.5 text-[#B7A7FF]"></i>
                                    <span>Admin Portal</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('home') ? 'bg-white text-[#7657F6] shadow-sm font-semibold' : 'text-[#777681] hover:text-[#17171D]' }}">Overview</a>
                            <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg text-[#777681] hover:text-[#17171D]">Library Preview</a>
                        @endauth
                    </nav>
                </div>

                <!-- Right: Actions & User menu -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->canUpload())
                            <a href="{{ route('upload.create') }}" 
                               class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-sm font-semibold transition shadow-sm hover:shadow">
                                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                                <span>Upload Lecture</span>
                            </a>
                        @endif

                        <!-- Notifications Bell -->
                        @php
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                        @endphp
                        <a href="{{ route('notifications.index') }}" 
                           class="relative p-2 rounded-xl text-[#777681] hover:text-[#17171D] hover:bg-white/80 transition"
                           title="Notifications">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            @if($unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#EF4444] ring-2 ring-[#F5F3EF]"></span>
                            @endif
                        </a>

                        <!-- User Profile Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" 
                                    @click.away="open = false" 
                                    class="flex items-center gap-2 p-1 pl-2 rounded-xl hover:bg-white/80 transition text-left focus:outline-none">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-semibold text-xs shadow-inner"
                                     style="background-color: {{ auth()->user()->avatar_color ?? '#7657F6' }}">
                                    {{ auth()->user()->initials }}
                                </div>
                                <div class="hidden sm:block leading-tight pr-1">
                                    <div class="font-semibold text-xs text-[#17171D] truncate max-w-[120px]">{{ auth()->user()->name }}</div>
                                    <div class="text-[10px] text-[#777681] uppercase tracking-wider">
                                        {{ auth()->user()->isAdmin() ? 'Admin' : (auth()->user()->isVerified() ? 'Verified' : 'Pending') }}
                                    </div>
                                </div>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#777681]"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-[#E5E2DC] shadow-xl py-2 z-50">
                                
                                <div class="px-4 py-2 border-b border-[#E5E2DC] mb-1">
                                    <p class="text-xs font-semibold text-[#17171D]">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-[#777681] truncate">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->batch)
                                        <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-[#F5F3EF] text-[#777681]">
                                            {{ auth()->user()->batch->batch_name }}
                                        </div>
                                    @endif
                                </div>

                                @if(auth()->user()->canAccessLectures())
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#17171D] hover:bg-[#F5F3EF] transition">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#777681]"></i>
                                        <span>Student Dashboard</span>
                                    </a>
                                    <a href="{{ route('my-uploads') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#17171D] hover:bg-[#F5F3EF] transition">
                                        <i data-lucide="folder-up" class="w-4 h-4 text-[#777681]"></i>
                                        <span>My Uploads</span>
                                    </a>
                                    <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#17171D] hover:bg-[#F5F3EF] transition">
                                        <i data-lucide="bookmark" class="w-4 h-4 text-[#777681]"></i>
                                        <span>Saved Lectures</span>
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <div class="border-t border-[#E5E2DC] my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#7657F6] font-semibold hover:bg-[#F0EDFF] transition">
                                        <i data-lucide="shield" class="w-4 h-4"></i>
                                        <span>Admin Cockpit</span>
                                    </a>
                                    <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#17171D] hover:bg-[#F5F3EF] transition">
                                        <i data-lucide="users" class="w-4 h-4 text-[#777681]"></i>
                                        <span>Verify Students</span>
                                    </a>
                                    <a href="{{ route('admin.recordings.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#17171D] hover:bg-[#F5F3EF] transition">
                                        <i data-lucide="film" class="w-4 h-4 text-[#777681]"></i>
                                        <span>Review Recordings</span>
                                    </a>
                                @endif

                                <div class="border-t border-[#E5E2DC] my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-[#EF4444] hover:bg-red-50 transition text-left">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl text-sm font-medium text-[#17171D] hover:bg-white/80 transition">Sign In</a>
                        <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-xl bg-[#17171D] hover:bg-[#24242D] text-white text-sm font-semibold transition shadow-sm">Get Verified</a>
                    @endauth
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

        @if(session('error') || $errors->any())
            <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-rose-900 text-sm shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        </div>
                        <div>
                            @if(session('error'))
                                <p class="font-medium">{{ session('error') }}</p>
                            @endif
                            @if($errors->any())
                                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Editorial Footer -->
    <footer class="border-t border-[#E5E2DC] bg-[#F5F3EF] mt-16 py-10 text-xs text-[#777681]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#17171D] text-white flex items-center justify-center font-bold text-xs">
                    UR
                </div>
                <div>
                    <span class="font-display font-bold text-[#17171D] text-sm">UniReplay</span>
                    <p class="text-[11px] text-[#777681]">University Lecture Sharing & Resource Platform · Built for Hackathon</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <span>Stack: Laravel 13 · PHP 8.5 · MySQL · HeidiSQL · Tailwind v4</span>
                <span class="inline-block w-1 h-1 rounded-full bg-[#E5E2DC]"></span>
                <span class="text-[#17171D] font-medium">Learn. Revisit. Replay.</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
