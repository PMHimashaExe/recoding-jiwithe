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
                    Join your university lecture archive.
                </h2>
                <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                    Once verified by your batch administrator, you can access all recordings, stream multi-part lectures, bookmark topics, and upload lecture recordings.
                </p>

                <div class="mt-8 space-y-3">
                    <div class="flex items-start gap-3 text-xs text-gray-300">
                        <div class="w-5 h-5 rounded-full bg-[#27B58A]/20 text-[#27B58A] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3"></i>
                        </div>
                        <span>Cross-batch lecture library access</span>
                    </div>
                    <div class="flex items-start gap-3 text-xs text-gray-300">
                        <div class="w-5 h-5 rounded-full bg-[#27B58A]/20 text-[#27B58A] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3"></i>
                        </div>
                        <span>Upload multi-part class recordings</span>
                    </div>
                    <div class="flex items-start gap-3 text-xs text-gray-300">
                        <div class="w-5 h-5 rounded-full bg-[#27B58A]/20 text-[#27B58A] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3"></i>
                        </div>
                        <span>Faculty & admin quality moderation</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 text-xs text-gray-400">
                <p class="leading-relaxed">
                    Note: Accounts start in <strong>Pending Verification</strong> status until the administrator assigned to your batch verifies your student ID.
                </p>
            </div>
        </div>

        <!-- Right Register Form -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-center">
            <div class="mb-6">
                <h1 class="font-display font-bold text-2xl text-[#17171D]">Student Registration</h1>
                <p class="text-xs text-[#777681] mt-1">Provide your student details to request verification</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-[#17171D] mb-1.5">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="e.g. Marcus Thorne"
                           required 
                           autofocus
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#17171D] mb-1.5">University Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="student@unireplay.edu"
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                    </div>

                    <div>
                        <label for="student_id_number" class="block text-xs font-semibold text-[#17171D] mb-1.5">Student ID Number</label>
                        <input type="text" 
                               id="student_id_number" 
                               name="student_id_number" 
                               value="{{ old('student_id_number') }}" 
                               placeholder="e.g. ST-2026-1044"
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                    </div>
                </div>

                <div>
                    <label for="batch_id" class="block text-xs font-semibold text-[#17171D] mb-1.5">Academic Batch</label>
                    <select id="batch_id" 
                            name="batch_id" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                        <option value="" disabled selected>Select your registered batch</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->batch_name }} (Graduating {{ $batch->graduation_date ? $batch->graduation_date->format('Y') : 'TBD' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#17171D] mb-1.5">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-[#17171D] mb-1.5">Confirm Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E2DC] bg-[#FAF8F5] text-sm text-[#17171D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30 focus:border-[#7657F6] transition">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3 rounded-xl bg-[#7657F6] hover:bg-[#6847ea] text-white text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Submit Registration for Verification</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[#E5E2DC] text-center">
                <p class="text-xs text-[#777681]">
                    Already have a verified account? 
                    <a href="{{ route('login') }}" class="font-semibold text-[#7657F6] hover:underline">Sign in here</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
