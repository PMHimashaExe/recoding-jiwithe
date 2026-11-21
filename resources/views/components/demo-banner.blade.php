<div class="bg-[#17171D] text-white border-b border-[#24242D] text-xs py-2 px-4 sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-[#27B58A] animate-pulse"></span>
            <span class="font-semibold tracking-wide text-gray-300 uppercase text-[10px]">Hackathon Judging Fast-Switcher:</span>
            @if(auth()->check())
                <span class="text-gray-400">Current: <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }} · {{ ucfirst(auth()->user()->status) }})</span>
            @else
                <span class="text-gray-400">Not logged in</span>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('demo.quick-login', 'admin') }}" 
               class="px-2.5 py-1 rounded bg-[#7657F6] hover:bg-[#6847ea] text-white transition font-medium flex items-center gap-1 shadow-sm {{ auth()->check() && auth()->user()->email === 'admin@unireplay.edu' ? 'ring-2 ring-white ring-offset-1 ring-offset-[#17171D]' : '' }}">
                <span>Admin (Dr. Sarah Chen · Batch '26)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'student') }}" 
               class="px-2.5 py-1 rounded bg-[#24242D] hover:bg-[#2e2e3a] text-gray-200 hover:text-white border border-gray-700 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'alex@student.unireplay.edu' ? 'ring-2 ring-[#7657F6]' : '' }}">
                <span>Verified Student (Alex · Batch '26)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'student2') }}" 
               class="px-2.5 py-1 rounded bg-[#24242D] hover:bg-[#2e2e3a] text-gray-200 hover:text-white border border-gray-700 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'elena@student.unireplay.edu' ? 'ring-2 ring-[#7657F6]' : '' }}">
                <span>Verified Student (Elena · Batch '25)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'pending') }}" 
               class="px-2.5 py-1 rounded bg-[#24242D] hover:bg-[#2e2e3a] text-amber-300 hover:text-amber-200 border border-amber-900/40 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'marcus@student.unireplay.edu' ? 'ring-2 ring-amber-400' : '' }}">
                <span>Pending Student (Marcus)</span>
            </a>
        </div>
    </div>
</div>
