<div class="relative" x-data="{ open: @entangle('showNotifications') }">
    <!-- Notification Bell Button -->
    <button 
        @click="$wire.toggleNotifications()"
        class="p-2 rounded-xl hover:bg-gray-800/50 transition-colors relative"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
        @endif
    </button>

    <!-- Notifications Dropdown -->
    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 max-h-[32rem] overflow-y-auto rounded-2xl border border-gray-800/60 bg-[#111111] shadow-2xl z-50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-800/60">
            <h3 class="font-semibold text-gray-200">Notifications</h3>
            @if(count($notifications) > 0)
                <button 
                    wire:click="clearAll" 
                    class="text-xs text-gray-400 hover:text-gray-200 transition"
                >
                    Clear All
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-gray-800/60">
            @forelse($notifications as $index => $notification)
                <div class="p-4 hover:bg-gray-900/40 transition {{ !$notification['read'] ? 'bg-indigo-900/10' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            @if($notification['type'] === 'monthly_completion')
                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-200">{{ $notification['title'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}</p>
                        </div>
                        @if(!$notification['read'])
                            <button 
                                wire:click="markAsRead({{ $index }})"
                                class="flex-shrink-0 text-xs text-indigo-400 hover:text-indigo-300"
                            >
                                Mark Read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-gray-400 text-sm">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
