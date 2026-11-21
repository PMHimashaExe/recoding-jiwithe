@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-2 space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between pb-6 border-b border-[#1E283D]">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                Notifications
            </h1>
            <p class="text-xs sm:text-sm text-[#94A3B8] mt-1">
                Updates on student verifications, recording approvals, and platform alerts.
            </p>
        </div>

        @if(auth()->user()->unreadNotifications()->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-[#0D1322] hover:bg-[#131D31] border border-cyan-500/30 text-xs font-semibold text-cyan-300 transition shadow-sm flex items-center gap-1.5">
                    <i data-lucide="check-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Notification Feed -->
    @if($notifications->isNotEmpty())
        <div class="editorial-card divide-y divide-[#1E283D] overflow-hidden">
            @foreach($notifications as $notif)
                <div class="p-4 sm:p-5 flex items-start justify-between gap-4 transition {{ !$notif->is_read ? 'bg-cyan-500/5' : 'bg-[#0D1322]' }}">
                    <div class="flex items-start gap-3.5">
                        
                        <!-- Notification Icon based on type -->
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5
                            {{ $notif->type === 'account_approved' || $notif->type === 'recording_approved' ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : '' }}
                            {{ $notif->type === 'account_rejected' || $notif->type === 'recording_rejected' ? 'bg-rose-500/15 text-rose-300 border border-rose-500/30' : '' }}
                            {{ $notif->type === 'system' ? 'bg-amber-500/15 text-amber-300 border border-amber-500/30' : '' }}">
                            @if(in_array($notif->type, ['account_approved', 'recording_approved']))
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            @elseif(in_array($notif->type, ['account_rejected', 'recording_rejected']))
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            @else
                                <i data-lucide="bell" class="w-4 h-4"></i>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-display font-semibold text-xs text-white">{{ $notif->title }}</h3>
                                @if(!$notif->is_read)
                                    <span class="w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_6px_#38BDF8]"></span>
                                @endif
                            </div>

                            <p class="text-xs text-[#94A3B8] leading-relaxed max-w-xl">
                                {{ $notif->message }}
                            </p>

                            <div class="flex items-center gap-3 pt-1 text-[11px] text-[#64748B]">
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                                @if($notif->link)
                                    <span>·</span>
                                    <form action="{{ route('notifications.read', $notif) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="font-semibold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1">
                                            <span>Open Details</span>
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!$notif->is_read)
                        <form action="{{ route('notifications.read', $notif) }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="p-1 rounded text-gray-500 hover:text-white" title="Mark as read">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="editorial-card p-12 text-center space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center mx-auto">
                <i data-lucide="bell-off" class="w-6 h-6"></i>
            </div>
            <h3 class="font-display font-bold text-base text-white">No notifications</h3>
            <p class="text-xs text-[#94A3B8]">You are completely caught up with all platform activity.</p>
        </div>
    @endif

</div>
@endsection
