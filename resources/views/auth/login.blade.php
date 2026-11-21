@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="grid grid-cols-1 md:grid-cols-12 overflow-hidden rounded-3xl bg-white border border-[#E5E2DC] shadow-xl">
        
        <!-- Left Editorial Branded Sidebar -->
        <div class="md:col-span-5 bg-[#17171D] text-white p-8 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-[#7657F6] text-white flex items-center justify-center font-bold text-sm">
                        <i data-lucide="play" class="w-4 h-4 fill-current ml-0.5"></i>
                    </div>
                    <span class="font-display font-bold text-lg text-white">UniReplay</span>
                </div>

                <h2 class="font-display font-extrabold text-2xl text-white tracking-tight leading-snug">
                    Access your university lectures anytime.
                </h2>
                <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                    A centralized archive of recorded lecture sessions, companion slides, practice source code, and study guides for verified university students.
                </p>
            </div>

            <!-- Demo Fast Logins Box for Judges -->
            <div class="mt-8 pt-6 border-t border-white/10 space-y-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#B7A7FF] block">
                    Hackathon Demo 1-Click Login:
                </span>
                
                <div class="space-y-2">
                    <a href="{{ route('demo.quick-login', 'admin') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#24242D] hover:bg-[#7657F6] text-white transition text-xs flex items-center justify-between border border-white/10">
                        <div>
                            <p class="font-semibold">Dr. Sarah Chen</p>
                            <p class="text-[10px] text-gray-400">Admin · Batch 2026 Moderator</p>
                        </div>
                        <i data-lucide="shield" class="w-4 h-4 text-[#B7A7FF]"></i>
                    </a>

                    <a href="{{ route('demo.quick-login', 'student') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#24242D] hover:bg-[#27B58A] text-white transition text-xs flex items-center justify-between border border-white/10">
                        <div>
                            <p class="font-semibold">Alex Rivera</p>
                            <p class="text-[10px] text-gray-400">Verified Student · Batch 2026</p>
                        </div>
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#27B58A]"></i>
                    </a>

                    <a href="{{ route('demo.quick-login', 'pending') }}" 
                       class="w-full text-left px-3 py-2 rounded-xl bg-[#24242D] hover:bg-amber-600 text-white transition text-xs flex items-center justify-between border border-white/10">
                        <div>
                            <p class="font-semibold">Marcus Thorne</p>
                            <p class="text-[10px] text-gray-400">Pending Student · Needs Approval</p>
                        </div>
                        <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Sign In Form -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-center">
            <div class="mb-6">
                <h1 class="font-display font-bold text-2xl text-[#17171D]">Welcome back</h1>
                <p class="text-xs text-[#777681] mt-1">Sign in with your university credentials</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-[#17171D] mb-1.5">University Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', 'admin@unireplay.edu') }}" 
                           required 
                           autofocus
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-[#17171D]">Password</label>
                        <span class="text-[11px] text-[#777681]">Default: password</span>
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           value="password"
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-[#7657F6] focus:ring-[#7657F6] border-[#E5E2DC]">
                        <span class="text-[#777681]">Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3 rounded-xl bg-[#17171D] hover:bg-[#24242D] text-white text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Sign In to UniReplay</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[#E5E2DC] text-center">
                <p class="text-xs text-[#777681]">
                    New university student? 
                    <a href="{{ route('register') }}" class="font-semibold text-[#7657F6] hover:underline">Register your account</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
