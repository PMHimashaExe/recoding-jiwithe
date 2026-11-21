@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-4 space-y-6">

    <!-- Header Section -->
    <div class="space-y-1">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#94A3B8] hover:text-white transition mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
            <span>Back to Dashboard</span>
        </a>
        <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
            Upload Lecture Recording
        </h1>
        <p class="text-xs sm:text-sm text-[#94A3B8]">
            Submit class recordings and study materials for moderation by your batch administrator.
        </p>
    </div>

    <!-- Upload Form Card -->
    <div class="editorial-card p-6 sm:p-8">
        <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. Subject & Batch Selection -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="subject_id" class="block text-xs font-semibold text-white mb-1.5">Academic Subject *</label>
                    <select id="subject_id" 
                            name="subject_id" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                        <option value="" disabled selected>Select course subject</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->subject_code }} — {{ $sub->subject_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="batch_id" class="block text-xs font-semibold text-white mb-1.5">Academic Batch *</label>
                    <select id="batch_id" 
                            name="batch_id" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                        <option value="" disabled selected>Select class batch</option>
                        @foreach($batches as $b)
                            <option value="{{ $b->id }}" {{ (old('batch_id') ?? auth()->user()->batch_id) == $b->id ? 'selected' : '' }}>
                                {{ $b->batch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. Lecture Date, Session Number, and Part Number -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="lecture_date" class="block text-xs font-semibold text-white mb-1.5">Lecture Date *</label>
                    <input type="date" 
                           id="lecture_date" 
                           name="lecture_date" 
                           value="{{ old('lecture_date', now()->format('Y-m-d')) }}" 
                           max="{{ now()->format('Y-m-d') }}"
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                </div>

                <div>
                    <label for="lecture_number" class="block text-xs font-semibold text-white mb-1.5">
                        Session / Lecture # *
                    </label>
                    <input type="number" 
                           id="lecture_number" 
                           name="lecture_number" 
                           value="{{ old('lecture_number', 1) }}" 
                           min="1" 
                           max="100"
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="part_number" class="block text-xs font-semibold text-white">Recording Part # *</label>
                        <span class="text-[10px] text-amber-400">Part 1, 2...</span>
                    </div>
                    <input type="number" 
                           id="part_number" 
                           name="part_number" 
                           value="{{ old('part_number', 1) }}" 
                           min="1" 
                           max="20"
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition">
                </div>
            </div>

            <!-- Helpful Notice about Sessions & Parts -->
            <div class="p-3.5 rounded-xl bg-[#09101E] border border-cyan-500/20 text-[11px] text-[#94A3B8] flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-cyan-400 shrink-0 mt-0.5"></i>
                <p>
                    <strong class="text-white">Session Grouping:</strong> Recordings for the same subject, batch, date, and lecture number are automatically grouped together. Each part number (e.g. Part 1, Part 2) represents a segment of that session.
                </p>
            </div>

            <!-- 3. Title & Description -->
            <div>
                <label for="title" class="block text-xs font-semibold text-white mb-1.5">Lecture Title / Topic *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       placeholder="e.g. Microservices Architecture & Event-Driven Message Queues" 
                       required 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-white mb-1.5">Lecture Description / Key Topics Covered</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="Summarize the key algorithms, slide sections, or practice problems covered in this recording..." 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">{{ old('description') }}</textarea>
            </div>

            <!-- 4. Video Recording Source (Google Drive or File Upload) -->
            <div class="space-y-4 pt-2 border-t border-[#1E283D]" x-data="{ uploadType: '{{ old('drive_url') ? 'drive' : 'drive' }}' }">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <label class="block text-xs font-semibold text-white">Video Recording Source *</label>
                    
                    <!-- Source Mode Toggle -->
                    <div class="inline-flex p-1 bg-[#090D18] rounded-xl border border-[#1E283D] text-[11px] font-medium">
                        <button type="button" 
                                @click="uploadType = 'drive'" 
                                :class="uploadType === 'drive' ? 'bg-cyan-500 text-white shadow-sm' : 'text-[#94A3B8] hover:text-white'"
                                class="px-3 py-1 rounded-lg transition flex items-center gap-1.5">
                            <i data-lucide="hard-drive" class="w-3.5 h-3.5"></i>
                            <span>Google Drive Link (Recommended)</span>
                        </button>
                        <button type="button" 
                                @click="uploadType = 'file'" 
                                :class="uploadType === 'file' ? 'bg-cyan-500 text-white shadow-sm' : 'text-[#94A3B8] hover:text-white'"
                                class="px-3 py-1 rounded-lg transition flex items-center gap-1.5">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Direct File Upload</span>
                        </button>
                    </div>
                </div>

                <!-- Google Drive Input Option -->
                <div x-show="uploadType === 'drive'" class="space-y-3">
                    <div class="p-4 rounded-2xl bg-[#090D18] border border-[#1E283D] space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0 mt-0.5 border border-cyan-500/30">
                                <i data-lucide="hard-drive" class="w-4 h-4"></i>
                            </div>
                            <div class="space-y-1">
                                <label for="drive_url" class="block text-xs font-bold text-white">Google Drive Shareable Link</label>
                                <p class="text-[11px] text-[#94A3B8]">
                                    Upload your lecture to Google Drive, set access to <strong class="text-white">"Anyone with the link can view"</strong>, and paste the link below.
                                </p>
                            </div>
                        </div>

                        <input type="text" 
                               id="drive_url" 
                               name="drive_url" 
                               value="{{ old('drive_url') }}" 
                               placeholder="https://drive.google.com/file/d/1a2b3c4d5e6f.../view?usp=sharing" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white placeholder-slate-500 focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition">

                        @error('drive_url')
                            <p class="text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center gap-2 text-[10px] text-[#94A3B8] bg-[#0A0E1A] px-3 py-2 rounded-xl border border-[#1E283D]">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-400 shrink-0"></i>
                            <span>Fast & Unlimited: Directly streams without filling local server disk storage.</span>
                        </div>
                    </div>
                </div>

                <!-- Direct File Upload Option -->
                <div x-show="uploadType === 'file'" class="space-y-2" style="display: none;">
                    <div class="border-2 border-dashed border-[#1E283D] hover:border-cyan-400 rounded-2xl p-6 text-center bg-[#090D18] transition">
                        <i data-lucide="video" class="w-8 h-8 text-amber-400 mx-auto mb-2"></i>
                        <p class="text-xs font-semibold text-white">Choose video file or drag and drop</p>
                        <p class="text-[11px] text-[#94A3B8] mt-0.5">MP4, WebM, MOV, or MKV (up to 1GB)</p>
                        <input type="file" 
                               id="video_file" 
                               name="video_file" 
                               accept="video/mp4,video/webm,video/quicktime,video/x-matroska"
                               class="mt-3 block w-full text-xs text-[#94A3B8] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#131D31] file:text-cyan-300 hover:file:bg-[#1A2640] cursor-pointer">
                    </div>
                </div>

                @error('video_source')
                    <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center gap-2 text-xs text-rose-300">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- 5. Optional Study Materials Attachment -->
            <div class="space-y-3 pt-2 border-t border-[#1E283D]">
                <div class="flex items-center gap-2">
                    <i data-lucide="paperclip" class="w-4 h-4 text-cyan-400"></i>
                    <h3 class="font-display font-semibold text-xs text-white">Attach Study Resource (Optional)</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="resource_title" class="block text-[11px] font-medium text-[#94A3B8] mb-1">Resource Title</label>
                        <input type="text" 
                               id="resource_title" 
                               name="resource_title" 
                               value="{{ old('resource_title') }}" 
                               placeholder="e.g. Session Slide Deck PDF or Source Code ZIP"
                               class="w-full px-3 py-2 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 transition">
                    </div>

                    <div>
                        <label for="resource_file" class="block text-[11px] font-medium text-[#94A3B8] mb-1">Resource File (PDF, ZIP, PPT)</label>
                        <input type="file" 
                               id="resource_file" 
                               name="resource_file" 
                               class="w-full text-xs text-[#94A3B8] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#131D31] file:text-cyan-300 cursor-pointer">
                    </div>
                </div>

                <div>
                    <label for="resource_link" class="block text-[11px] font-medium text-[#94A3B8] mb-1">Or External Documentation Link</label>
                    <input type="url" 
                           id="resource_link" 
                           name="resource_link" 
                           value="{{ old('resource_link') }}" 
                           placeholder="https://docs.example.com/reference"
                           class="w-full px-3 py-2 rounded-xl border border-[#1E283D] bg-[#0A0E1A] text-xs text-white focus:bg-[#0E1528] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 transition">
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-4 border-t border-[#1E283D] flex items-center justify-between">
                <span class="text-xs text-[#94A3B8]">Initial status will be <strong class="text-amber-400">Pending Moderation</strong></span>
                <button type="submit" 
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white text-xs font-semibold transition shadow-[0_0_20px_rgba(245,158,11,0.4)] flex items-center gap-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>Submit for Admin Review</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
