@extends('layouts.admin')

@section('content')
<div class="space-y-6 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#1E283D]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                Student Verification Management
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Review and approve newly registered student profiles to grant lecture archive access.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">Moderator Batch: <strong class="text-sky-400">{{ auth()->user()->batch ? auth()->user()->batch->batch_name : 'All Batches' }}</strong></span>
        </div>
    </div>

    <!-- Status Tabs & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Status Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.index', ['status' => 'pending', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-black font-bold shadow-md shadow-amber-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Pending Verification</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-black text-amber-400 font-bold' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'verified', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'verified' ? 'bg-emerald-500 text-black font-bold shadow-md shadow-emerald-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Verified Students</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'verified' ? 'bg-black text-emerald-400 font-bold' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' }}">
                    {{ $counts['verified'] }}
                </span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'rejected', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'rejected' ? 'bg-rose-500 text-white font-bold shadow-md shadow-rose-500/20' : 'bg-[#0D1322] text-slate-400 hover:text-white border border-[#1E283D]' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'rejected' ? 'bg-black text-rose-400 font-bold' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                    {{ $counts['rejected'] }}
                </span>
            </a>
        </div>

        <!-- Search & Batch Filter Form -->
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search name, email, ID..." 
                       class="px-3 py-1.5 pl-8 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
            </div>

            <select name="batch_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-[#1E283D] bg-[#0D1322] text-xs text-white focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                <option value="">All Batches</option>
                @foreach($batches as $b)
                    <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>
                        {{ $b->batch_name }}
                    </option>
                @endforeach
            </select>
        </form>

    </div>

    <!-- Students Table -->
    @if($students->isNotEmpty())
        <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#090D18] border-b border-[#1E283D] text-slate-400 uppercase text-[10px] font-semibold tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Student</th>
                            <th class="px-5 py-3.5">Student ID</th>
                            <th class="px-5 py-3.5">Batch</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Registered</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E283D]">
                        @foreach($students as $st)
                            <tr class="hover:bg-[#131B2E]/70 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white shrink-0 border border-white/10"
                                             style="background-color: {{ $st->avatar_color ?? '#38BDF8' }}">
                                            {{ $st->initials }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-xs">{{ $st->name }}</p>
                                            <p class="text-[11px] text-slate-400">{{ $st->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-mono font-medium text-slate-200">
                                    {{ $st->student_id_number ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-slate-400">
                                    {{ $st->batch ? $st->batch->batch_name : 'No batch' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($st->status === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            <i data-lucide="check" class="w-3 h-3"></i> Verified
                                        </span>
                                    @elseif($st->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            <i data-lucide="clock" class="w-3 h-3"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                            <i data-lucide="x" class="w-3 h-3"></i> Rejected
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-slate-400">
                                    {{ $st->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2" x-data="{ rejectOpen: false }">
                                        @if($st->status !== 'verified')
                                            <form action="{{ route('admin.students.approve', $st) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-black font-bold transition text-xs shadow-md shadow-emerald-500/20 flex items-center gap-1">
                                                    <i data-lucide="check" class="w-3 h-3 stroke-[3]"></i>
                                                    <span>Verify</span>
                                                </button>
                                            </form>
                                        @endif

                                        @if($st->status !== 'rejected')
                                            <button @click="rejectOpen = true" 
                                                    class="px-2.5 py-1.5 rounded-lg border border-[#1E283D] text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 text-xs transition">
                                                <span>Reject</span>
                                            </button>

                                            <!-- Modal for Rejection Reason -->
                                            <div x-show="rejectOpen" 
                                                 x-cloak 
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4">
                                                <div @click.away="rejectOpen = false" class="bg-[#0D1322] border border-[#1E283D] rounded-2xl max-w-md w-full p-6 text-left space-y-4 shadow-2xl">
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="font-display font-bold text-base text-white">Decline Verification Request</h3>
                                                        <button @click="rejectOpen = false" class="text-slate-400 hover:text-white">
                                                            <i data-lucide="x" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-slate-400">
                                                        Please explain why {{ $st->name }}'s registration is being declined. This message will be sent to the student.
                                                    </p>
                                                    <form action="{{ route('admin.students.reject', $st) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <textarea name="reason" 
                                                                  rows="3" 
                                                                  required 
                                                                  class="w-full p-3 rounded-xl border border-[#1E283D] bg-[#070913] text-xs text-white focus:outline-none focus:ring-2 focus:ring-rose-500/40"
                                                                  placeholder="e.g. Student ID could not be verified in the university registrar records."></textarea>

                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button" @click="rejectOpen = false" class="px-4 py-2 rounded-xl border border-[#1E283D] text-xs text-slate-400 hover:text-white">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs shadow-md">
                                                                Confirm Decline
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#1E283D]">
                {{ $students->links() }}
            </div>
        </div>
    @else
        <div class="rounded-2xl bg-[#0D1322] border border-[#1E283D] p-12 text-center space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center mx-auto">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-white">No students found</h3>
            <p class="text-xs text-slate-400">No student accounts match the selected status or filters.</p>
        </div>
    @endif

</div>
@endsection
