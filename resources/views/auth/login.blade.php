@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="grid grid-cols-1 md:grid-cols-12 overflow-hidden rounded-3xl bg-[#0D1322] border border-[#1E283D] shadow-2xl">
        
        <!-- Left Editorial Branded Sidebar -->
        <div class="md:col-span-5 bg-[#070913] text-white p-8 flex flex-col justify-between border-r border-[#1E283D] relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-cyan-500 text-white flex items-center justify-center font-bold text-sm shadow-[0_0_12px_rgba(245,158,11,0.4)]">
                        <i data-lucide="play" class="w-4 h-4 fill-current ml-0.5 text-white"></i>
                    </div>
                    <span class="font-display font-bold text-lg text-white">UniReplay</span>
                </div>

                <h2 class="font-display font-extrabold text-2xl text-white tracking-tight leading-snug">
                    Access your university lectures anytime.
                </h2>
                <p class="text-xs text-[#94A3B8] mt-3 leading-relaxed">
                    A centralized archive of recorded lecture sessions, companion slides, practice source code, and study guides for verified university students.
                </p>
            </div>

            <!-- Demo Fast Logins Box for Judges -->
            <div class="mt-8 pt-6 border-t border-[#1E283D] space-y-3 relative z-10">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400 block">
                    Hackathon Demo 1-Click Login:
                </span>
                
                <div class="space-y-2">
                    <a href="{{ route('demo.quick-login', 'admin') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#0E1626] hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-500 text-white transition text-xs flex items-center justify-between border border-amber-500/30 group">
                        <div>
                            <p class="font-semibold text-white">Dr. Sarah Chen</p>
                            <p class="text-[10px] text-amber-300/80 group-hover:text-white">Admin · Batch 2026 Moderator</p>
                        </div>
                        <i data-lucide="shield" class="w-4 h-4 text-amber-400 group-hover:text-white"></i>
                    </a>

                    <a href="{{ route('demo.quick-login', 'student') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#0E1626] hover:bg-cyan-600 text-white transition text-xs flex items-center justify-between border border-cyan-500/30 group">
                        <div>
                            <p class="font-semibold text-white">Alex Rivera</p>
                            <p class="text-[10px] text-cyan-300/80 group-hover:text-white">Verified Student · Batch 2026</p>
                        </div>
                        <i data-lucide="check-circle" class="w-4 h-4 text-cyan-400 group-hover:text-white"></i>
                    </a>

                    <a href="{{ route('demo.quick-login', 'pending') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#0E1626] hover:bg-amber-600 text-white transition text-xs flex items-center justify-between border border-amber-500/30 group">
                        <div>
                            <p class="font-semibold text-white">Marcus Thorne</p>
                            <p class="text-[10px] text-amber-300/80 group-hover:text-white">Pending Student · Needs Approval</p>
                        </div>
                        <i data-lucide="clock" class="w-4 h-4 text-amber-400 group-hover:text-white"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Sign In Form -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-center bg-[#0D1322]">
            <div class="mb-6">
                <h1 class="font-display font-bold text-2xl text-white">Welcome back</h1>
                <p class="text-xs text-[#94A3B8] mt-1">Sign in with your university credentials</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-white mb-1.5">University Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', 'admin@unireplay.edu') }}" 
                           required 
                           autofocus
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-sm text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-white">Password</label>
                        <span class="text-[11px] text-amber-400 font-mono">Default: password</span>
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           value="password"
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-sm text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-amber-500 focus:ring-amber-500 border-[#1E283D] bg-[#0A0E1A]">
                        <span class="text-[#94A3B8]">Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-white text-sm font-semibold transition shadow-[0_0_20px_rgba(245,158,11,0.35)] flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Sign In to UniReplay</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[#1E283D] text-center">
                <p class="text-xs text-[#94A3B8]">
                    New university student? 
                    <a href="{{ route('register') }}" class="font-semibold text-cyan-400 hover:text-cyan-300 hover:underline">Register your account</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
