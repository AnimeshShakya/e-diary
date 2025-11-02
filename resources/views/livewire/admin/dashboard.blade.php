<div class="space-y-8">
    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Total Users Card --}}
        <div 
            class="group relative p-6 rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-indigo-500/50 transition-all duration-300 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0ms"
        >
            {{-- Glow effect --}}
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-colors">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-400 mb-1">Total Users</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">{{ number_format($usersCount) }}</p>
            </div>
        </div>

        {{-- Total Diaries Card --}}
        <div 
            class="group relative p-6 rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-purple-500/50 transition-all duration-300 overflow-hidden animate-fade-in-up"
            style="animation-delay: 100ms"
        >
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 transition-colors">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-400 mb-1">Total Diaries</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">{{ number_format($diariesCount) }}</p>
            </div>
        </div>

        {{-- Flagged Content Card --}}
        <div 
            class="group relative p-6 rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-red-500/50 transition-all duration-300 overflow-hidden animate-fade-in-up"
            style="animation-delay: 200ms"
        >
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-red-500/10 group-hover:bg-red-500/20 transition-colors">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path>
                        </svg>
                    </div>
                    @if($flaggedCount > 0)
                        <span class="text-xs font-medium text-red-400 bg-red-400/10 px-2 py-1 rounded-lg">Action needed</span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-gray-400 mb-1">Flagged Content</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-red-400 to-orange-400 bg-clip-text text-transparent">{{ number_format($flaggedCount) }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Activity Section --}}
    <div class="rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 overflow-hidden animate-fade-in-up" style="animation-delay: 300ms">
        <div class="p-6 border-b border-gray-800/50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                    </svg>

                    Checklist
                </h2>
                <button class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">View all</button>
            </div>
        </div>
        
        <div class="p-6">
            @if(count($recentActivity) > 0)
                <div class="space-y-4">
                    @foreach($recentActivity as $act)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-900/30 hover:bg-gray-900/50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold">{{ substr($act->admin_id ?? 'A', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-200">{{ $act->action }}</p>
                                <p class="text-xs text-gray-500">Admin ID: {{ $act->admin_id }}</p>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-gray-400 transition-colors">
                                {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-800/50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">No recent activity</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in-up" style="animation-delay: 400ms">
        <a href="{{ route('admin.users.index') }}" class="p-4 rounded-xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-indigo-500/50 transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-colors">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Manage Users</span>
            </div>
        </a>

        <a href="{{ route('admin.diaries.index') }}" class="p-4 rounded-xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-purple-500/50 transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-purple-500/10 group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">View Diaries</span>
            </div>
        </a>

        @php($moderationUrl = Route::has('admin.moderation') ? route('admin.moderation') : null)
        @if($moderationUrl)
            <a href="{{ $moderationUrl }}" class="p-4 rounded-xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-red-500/50 transition-all duration-300 group">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-red-500/10 group-hover:bg-red-500/20 transition-colors">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Moderation Queue</span>
                </div>
            </a>
        @else
            <div class="p-4 rounded-xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 opacity-60 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-red-500/5">
                        <svg class="w-5 h-5 text-red-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Moderation Coming Soon</span>
                </div>
            </div>
        @endif

        <button class="p-4 rounded-xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] border border-gray-800/50 hover:border-gray-700 transition-all duration-300 group text-left">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-gray-700/10 group-hover:bg-gray-700/20 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Settings</span>
            </div>
        </button>
    </div>
</div>
