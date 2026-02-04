<x-dashboard.card wire:init="load" class="hover:border-indigo-500/50">

    {{-- Loading skeleton --}}
    <div wire:loading.delay class="animate-pulse">
        <div class="h-6 bg-gray-800 rounded mb-4 w-1/2"></div>
        <div class="h-32 bg-gray-800 rounded mb-4"></div>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="h-20 bg-gray-800 rounded"></div>
            <div class="h-20 bg-gray-800 rounded"></div>
            <div class="h-20 bg-gray-800 rounded"></div>
        </div>
    </div>

    {{-- Actual content --}}
    <div wire:loading.remove.delay>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-200">7-Day Performance Score</h3>
            <span class="text-sm text-gray-400">Last Week Overview</span>
        </div>

        @if($hasData)
            <div class="space-y-6">

                <div class="relative p-8 rounded-xl bg-gradient-to-br from-purple-900/20 to-pink-900/20
                    border border-purple-500/20 backdrop-blur-sm overflow-hidden">

                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 animate-pulse"></div>

                    <div class="relative z-10 text-center">
                        <div class="text-xs uppercase tracking-widest text-purple-400 mb-4 font-medium">
                            7-Day Average Score
                        </div>

                        <div class="flex justify-center items-center mb-4">
                            <div class="relative w-32 h-32">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-tr 
                                    from-purple-500 to-pink-500 animate-spin-slow blur-md opacity-50"></div>

                                <div class="relative w-full h-full rounded-full p-[3px]
                                    bg-gradient-to-tr from-purple-500 to-pink-500">
                                    <div class="w-full h-full bg-[#1a1a1a] rounded-full 
                                        flex items-center justify-center">
                                        <span class="text-5xl font-bold bg-gradient-to-br 
                                            from-purple-400 to-pink-400 bg-clip-text text-transparent">
                                            {{ $averageScore }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-400 mb-4">Out of 10</div>

                        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold
                            backdrop-blur-sm {{ $stats['badgeClass'] ?? '' }}">
                            {{ $stats['badgeText'] ?? '' }}
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gradient-to-br from-green-900/20 to-green-900/10 
                        border border-green-500/20 text-center backdrop-blur-sm">
                        <div class="text-3xl font-bold text-green-400 mb-1">
                            {{ $stats['goodDays'] }}
                        </div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Good Days</div>
                    </div>

                    <div class="p-4 rounded-xl bg-gradient-to-br from-blue-900/20 to-blue-900/10 
                        border border-blue-500/20 text-center backdrop-blur-sm">
                        <div class="text-3xl font-bold text-blue-400 mb-1">
                            {{ $stats['total'] }}
                        </div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Total Entries</div>
                    </div>

                    <div class="p-4 rounded-xl bg-gradient-to-br from-purple-900/20 to-purple-900/10 
                        border border-purple-500/20 text-center backdrop-blur-sm">
                        <div class="text-3xl font-bold text-purple-400 mb-1">
                            {{ $stats['max'] }}
                        </div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Best Score</div>
                    </div>
                </div>

                <!-- Daily List with Prefetch -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Daily Scores
                    </h4>

                    <div class="space-y-2 max-h-[320px] overflow-y-auto pr-2 scrollbar-thin
                        scrollbar-thumb-gray-700 scrollbar-track-gray-900/50">

                        @foreach($dailyScores as $d)
                            {{-- Prefetch on hover for instant interaction --}}
                            <button 
                                wire:click.prefetch="switchToDate('{{ $d['fullDate'] }}')"
                                type="button"
                                class="w-full group flex items-center justify-between p-4 rounded-xl
                                    bg-gray-900/40 border border-gray-800/50 hover:border-indigo-500/30
                                    hover:bg-gray-900/60 transition-all duration-200 cursor-pointer">

                                <div class="flex-1 text-left">
                                    <div class="font-semibold text-gray-200 text-sm">{{ $d['fullDate'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $d['date'] }}</div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-1.5 rounded-lg min-w-[55px] text-center text-base font-bold
                                        backdrop-blur-sm {{ $d['class'] }}">
                                        {{ $d['score'] }}
                                    </span>
                                    
                                    {{-- View detail icon --}}
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-400 transition-colors" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>

                            </button>
                        @endforeach

                    </div>
                </div>

            </div>
        @else
            <div class="flex items-center justify-center p-12 rounded-xl bg-gray-900/40 border border-gray-800/50 min-h-[24rem]">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-800/50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-400 text-base mb-2">No diary entries found in the last 7 days</p>
                    <p class="text-gray-500 text-sm mb-6">Start creating diary entries to see your performance!</p>
                    
                    {{-- Prefetch on hover --}}
                    <a href="{{ route('diaries.create') }}" 
                       wire:navigate.prefetch
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg font-medium transition-all duration-200 shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Diary Entry
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-dashboard.card>