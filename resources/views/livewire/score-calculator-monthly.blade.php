<x-dashboard.card wire:init="load" class="hover:border-indigo-500/50">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-200">7-Day Performance Score</h3>
        <span class="text-sm text-gray-400">Last Week Overview</span>
    </div>

    @if($hasData)
        <div class="space-y-6">
            <!-- 30 Day Average Score - Hero Section -->
            <div class="relative p-8 rounded-xl bg-gradient-to-br from-purple-900/20 to-pink-900/20 border border-purple-500/20 backdrop-blur-sm overflow-hidden">
                <!-- Animated Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 animate-pulse"></div>
                
                <div class="relative z-10 text-center">
                    <div class="text-xs uppercase tracking-widest text-purple-400 mb-4 font-medium">
                        7-Day Average Score
                    </div>

                    <div class="flex justify-center items-center mb-4">
                        <div class="relative">
                            <!-- Animated Ring -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 animate-spin-slow blur-md opacity-50"></div>
                            
                            <!-- Score Circle -->
                            <div class="relative w-32 h-32 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 p-[3px]">
                                <div class="w-full h-full bg-[#1a1a1a] rounded-full flex items-center justify-center">
                                    <span class="text-5xl font-bold bg-gradient-to-br from-purple-400 to-pink-400 bg-clip-text text-transparent">
                                        {{ $averageScore }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-400 mb-4">Out of 10</div>

                    <!-- Performance Badge -->
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold backdrop-blur-sm
                        @if($averageScore >= 9) bg-green-500/20 text-green-400 border border-green-500/30
                        @elseif($averageScore >= 7) bg-blue-500/20 text-blue-400 border border-blue-500/30
                        @elseif($averageScore >= 5) bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                        @else bg-red-500/20 text-red-400 border border-red-500/30
                        @endif
                    ">
                        @if($averageScore >= 9) ⭐ Excellent Performance
                        @elseif($averageScore >= 7) ✅ Good Progress
                        @elseif($averageScore >= 5) ⚡ Keep Going
                        @else ⚠️ Needs Improvement
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-gradient-to-br from-green-900/20 to-green-900/10 border border-green-500/20 text-center backdrop-blur-sm">
                    <div class="text-3xl font-bold text-green-400 mb-1">
                        {{ collect($monthlyScores)->where('score', '>=', 7)->count() }}
                    </div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Good Days</div>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-900/20 to-blue-900/10 border border-blue-500/20 text-center backdrop-blur-sm">
                    <div class="text-3xl font-bold text-blue-400 mb-1">
                        {{ count($monthlyScores) }}
                    </div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Total Entries</div>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-purple-900/20 to-purple-900/10 border border-purple-500/20 text-center backdrop-blur-sm">
                    <div class="text-3xl font-bold text-purple-400 mb-1">
                        {{ collect($monthlyScores)->max('score') ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Best Score</div>
                </div>
            </div>

            <!-- Daily Scores List -->
            <div>
                <h4 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-2 uppercase tracking-wide">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Daily Scores
                </h4>

                <div class="space-y-2 max-h-[320px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900/50">
                    @foreach($monthlyScores as $dayScore)
                        <div class="group flex items-center justify-between p-4 rounded-xl bg-gray-900/40 border border-gray-800/50 hover:border-indigo-500/30 hover:bg-gray-900/60 transition-all duration-200">
                            <!-- Date -->
                            <div class="flex-1">
                                <div class="font-semibold text-gray-200 text-sm">{{ $dayScore['fullDate'] }}</div>
                                <div class="text-xs text-gray-500">{{ $dayScore['date'] }}</div>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Score Badge -->
                                <div class="flex flex-col items-end">
                                    <span class="px-4 py-1.5 rounded-lg min-w-[55px] text-center text-base font-bold backdrop-blur-sm
                                        @if($dayScore['score'] >= 9) bg-green-500/20 text-green-400 border border-green-500/30
                                        @elseif($dayScore['score'] >= 7) bg-blue-500/20 text-blue-400 border border-blue-500/30
                                        @elseif($dayScore['score'] >= 5) bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                        @else bg-red-500/20 text-red-400 border border-red-500/30
                                        @endif
                                    ">
                                        {{ $dayScore['score'] }}
                                    </span>
                                </div>

                                <!-- Emoji Mood -->
                                <span class="text-2xl opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-all duration-200">
                                    @if($dayScore['score'] >= 9) 
                                    @elseif($dayScore['score'] >= 7) 
                                    @elseif($dayScore['score'] >= 5) 
                                    @else 
                                    @endif
                                </span>
                            </div>
                        </div>
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
                <a href="{{ route('diaries.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg font-medium transition-all duration-200 shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Diary Entry
                </a>
            </div>
        </div>
    @endif
</x-dashboard.card>
