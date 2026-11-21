<div class="bg-[#04060A] text-white border-b border-[#141C2E] text-xs py-2 px-4 sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-[#10B981] shadow-[0_0_8px_#10B981] animate-pulse"></span>
            <span class="font-semibold tracking-wide text-gray-300 uppercase text-[10px]">Hackathon Judging Fast-Switcher:</span>
            @if(auth()->check())
                <span class="text-gray-400">Current: <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }} · {{ ucfirst(auth()->user()->status) }})</span>
            @else
                <span class="text-gray-400">Not logged in</span>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('demo.quick-login', 'admin') }}" 
               class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white transition font-semibold flex items-center gap-1 shadow-[0_0_12px_rgba(245,158,11,0.3)] {{ auth()->check() && auth()->user()->email === 'admin@unireplay.edu' ? 'ring-2 ring-white' : '' }}">
                <span>Admin (Dr. Sarah Chen · Batch '26)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'student') }}" 
               class="px-2.5 py-1 rounded-lg bg-[#0E1626] hover:bg-[#152037] text-cyan-300 hover:text-white border border-cyan-500/40 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'alex@student.unireplay.edu' ? 'ring-2 ring-cyan-400 shadow-[0_0_10px_rgba(56,189,248,0.4)]' : '' }}">
                <span>Verified Student (Alex · Batch '26)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'student2') }}" 
               class="px-2.5 py-1 rounded-lg bg-[#0E1626] hover:bg-[#152037] text-cyan-300 hover:text-white border border-cyan-500/40 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'elena@student.unireplay.edu' ? 'ring-2 ring-cyan-400 shadow-[0_0_10px_rgba(56,189,248,0.4)]' : '' }}">
                <span>Verified Student (Elena · Batch '25)</span>
            </a>
            <a href="{{ route('demo.quick-login', 'pending') }}" 
               class="px-2.5 py-1 rounded-lg bg-[#0E1626] hover:bg-[#152037] text-amber-300 hover:text-amber-200 border border-amber-500/40 transition font-medium flex items-center gap-1 {{ auth()->check() && auth()->user()->email === 'marcus@student.unireplay.edu' ? 'ring-2 ring-amber-400 shadow-[0_0_10px_rgba(245,158,11,0.4)]' : '' }}">
                <span>Pending Student (Marcus)</span>
            </a>
        </div>
    </div>
</div>
