@extends('layouts.admin')

@section('content')
<div class="space-y-6 py-2">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E5E2DC]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[#17171D] tracking-tight">
                Student Verification Management
            </h1>
            <p class="text-xs sm:text-sm text-[#777681] mt-1">
                Review and approve newly registered student profiles to grant lecture archive access.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-[#777681]">Moderator: <strong class="text-[#7657F6]">{{ auth()->user()->batch ? auth()->user()->batch->batch_name : 'All Batches' }}</strong></span>
        </div>
    </div>

    <!-- Status Tabs & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Status Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.index', ['status' => 'pending', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Pending Verification</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-white text-amber-700 font-bold' : 'bg-amber-100 text-amber-800' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'verified', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'verified' ? 'bg-[#27B58A] text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Verified Students</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'verified' ? 'bg-white text-[#27B58A] font-bold' : 'bg-[#EBF8F4] text-[#27B58A]' }}">
                    {{ $counts['verified'] }}
                </span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'rejected', 'batch_id' => $batchId]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ $status === 'rejected' ? 'bg-rose-500 text-white shadow-sm' : 'bg-white text-[#777681] hover:text-[#17171D] border border-[#E5E2DC]' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'rejected' ? 'bg-white text-rose-700 font-bold' : 'bg-rose-100 text-rose-800' }}">
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
                       class="px-3 py-1.5 pl-8 rounded-xl border border-[#E5E2DC] bg-white text-xs text-[#17171D] focus:outline-none focus:ring-2 focus:ring-[#7657F6]/30">
                <i data-lucide="search" class="w-3.5 h-3.5 text-[#777681] absolute left-2.5 top-2.5"></i>
            </div>

            <select name="batch_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-[#E5E2DC] bg-white text-xs text-[#17171D] focus:outline-none">
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
        <div class="editorial-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] border-b border-[#E5E2DC] text-[#777681] uppercase text-[10px] font-semibold tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Student</th>
                            <th class="px-5 py-3.5">Student ID</th>
                            <th class="px-5 py-3.5">Batch</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Registered</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E2DC]">
                        @foreach($students as $st)
                            <tr class="hover:bg-[#FAF8F5]/60 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white shrink-0"
                                             style="background-color: {{ $st->avatar_color ?? '#7657F6' }}">
                                            {{ $st->initials }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#17171D] text-xs">{{ $st->name }}</p>
                                            <p class="text-[11px] text-[#777681]">{{ $st->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-mono font-medium text-[#17171D]">
                                    {{ $st->student_id_number ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-[#777681]">
                                    {{ $st->batch ? $st->batch->batch_name : 'No batch' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($st->status === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF8F4] text-[#27B58A] border border-[#27B58A]/30">
                                            <i data-lucide="check" class="w-3 h-3"></i> Verified
                                        </span>
                                    @elseif($st->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i data-lucide="clock" class="w-3 h-3"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i data-lucide="x" class="w-3 h-3"></i> Rejected
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-[#777681]">
                                    {{ $st->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2" x-data="{ rejectOpen: false }">
                                        @if($st->status !== 'verified')
                                            <form action="{{ route('admin.students.approve', $st) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-lg bg-[#27B58A] hover:bg-[#209c76] text-white font-semibold transition text-xs shadow-sm flex items-center gap-1">
                                                    <i data-lucide="check" class="w-3 h-3"></i>
                                                    <span>Verify</span>
                                                </button>
                                            </form>
                                        @endif

                                        @if($st->status !== 'rejected')
                                            <button @click="rejectOpen = true" 
                                                    class="px-2.5 py-1.5 rounded-lg border border-[#E5E2DC] text-[#777681] hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 text-xs transition">
                                                <span>Reject</span>
                                            </button>

                                            <!-- Modal for Rejection Reason -->
                                            <div x-show="rejectOpen" 
                                                 x-cloak 
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                                                <div @click.away="rejectOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 text-left space-y-4 shadow-2xl">
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="font-display font-bold text-base text-[#17171D]">Decline Verification Request</h3>
                                                        <button @click="rejectOpen = false" class="text-gray-400 hover:text-gray-600">
                                                            <i data-lucide="x" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-[#777681]">
                                                        Please explain why {{ $st->name }}'s registration is being declined. This message will be sent to the student.
                                                    </p>
                                                    <form action="{{ route('admin.students.reject', $st) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <textarea name="reason" 
                                                                  rows="3" 
                                                                  required 
                                                                  class="w-full p-3 rounded-xl border border-[#E5E2DC] text-xs text-[#17171D] focus:outline-none focus:ring-2 focus:ring-rose-500/30"
                                                                  placeholder="e.g. Student ID could not be verified in the university registrar records."></textarea>

                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button" @click="rejectOpen = false" class="px-4 py-2 rounded-xl border border-[#E5E2DC] text-xs text-[#777681]">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-sm">
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

            <div class="p-4 border-t border-[#E5E2DC]">
                {{ $students->links() }}
            </div>
        </div>
    @else
        <div class="editorial-card p-12 text-center space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-[#F0EDFF] text-[#7657F6] flex items-center justify-center mx-auto">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-[#17171D]">No students found</h3>
            <p class="text-xs text-[#777681]">No student accounts match the selected status or filters.</p>
        </div>
    @endif

</div>
@endsection
