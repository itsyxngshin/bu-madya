<div class="relative" x-data="{ open: false }" wire:poll.15s>
    {{-- Notification Bell Button --}}
    <button @click="open = !open; if(open) $wire.markAsRead()" @click.away="open = false" class="p-2 text-gray-500 hover:text-iba-teal dark:text-gray-400 dark:hover:text-iba-teal transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 relative focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        {{-- Unread Badge --}}
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-iba-red opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-iba-red text-[8px] text-white font-black items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            </span>
        @endif
    </button>

    {{-- Notification Dropdown --}}
    <div x-show="open" x-cloak style="display: none;" class="absolute right-0 mt-3 w-80 bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] z-50 overflow-hidden">
        
        <div class="p-4 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h3 class="text-sm font-black uppercase tracking-widest text-iba-black dark:text-white">System Alerts</h3>
            @if($unreadCount > 0)
                <span class="text-[9px] font-bold text-gray-500 uppercase">{{ $unreadCount }} New</span>
            @endif
        </div>
        
        <div class="max-h-80 overflow-y-auto bg-white dark:bg-[#1A1617]">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link }}" class="block p-4 border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal/10 transition-colors {{ !$notif->is_read ? 'bg-gray-50 dark:bg-gray-900 border-l-4 border-l-iba-orange' : '' }}">
                    <div class="flex items-start gap-3">
                        <span class="text-lg shrink-0">{{ $notif->type === 'announcement' ? '📢' : ($notif->type === 'mention' ? '👋' : '💬') }}</span>
                        <div>
                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-snug">{{ $notif->message }}</p>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mt-1">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-xs font-bold text-gray-500 uppercase tracking-widest bg-gray-50 dark:bg-gray-900">
                    No recent alerts.
                </div>
            @endforelse
        </div>
        
        <div class="p-3 border-t-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-center">
            <a href="{{ route('ibalong.community-logs') }}" class="text-[10px] font-black uppercase tracking-widest text-iba-teal hover:text-teal-700 transition-colors">Open Community Logs →</a>
        </div>
    </div>
</div>