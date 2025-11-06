<div class="p-6 bg-white dark:bg-[#1a1a1a] rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">📊 Last 30 Days Performance</h3>

    @if($hasData)
        <!-- Average Score Display -->
        <div class="mb-6 p-6 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border-2 border-purple-200 dark:border-purple-700">
            <div class="text-center">
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">30-Day Average Score</div>
                <div class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                    {{ $averageScore }}
                </div>
                <div class="text-lg text-gray-600 dark:text-gray-400">out of 10</div>
                
                <!-- Rating Badge -->
                <div class="mt-4 inline-block px-4 py-2 rounded-full text-sm font-semibold
                    @if($averageScore >= 9) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                    @elseif($averageScore >= 7) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                    @elseif($averageScore >= 5) bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                    @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                    @endif
                ">
                    @if($averageScore >= 9) Excellent Performance
                    @elseif($averageScore >= 7) Good Performance
                    @elseif($averageScore >= 5) Fair Performance
                    @else Needs Improvement
                    @endif
                </div>
            </div>
        </div>

        <!-- Daily Scores List -->
        <div class="space-y-3">
            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Daily Scores:</h4>
            <div class="grid gap-3 max-h-96 overflow-y-auto">
                @foreach($monthlyScores as $dayScore)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $dayScore['fullDate'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $dayScore['date'] }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Score Badge -->
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($dayScore['score'] >= 9) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                @elseif($dayScore['score'] >= 7) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                @elseif($dayScore['score'] >= 5) bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                                @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                @endif
                            ">
                                {{ $dayScore['score'] }}
                            </span>
                            
                            <!-- Emoji -->
                            <span class="text-2xl">
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

        <!-- Statistics -->
        <div class="mt-6 grid grid-cols-3 gap-4">
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ collect($monthlyScores)->where('score', '>=', 7)->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Good Days</div>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ count($monthlyScores) }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Entries</div>
            </div>
            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ collect($monthlyScores)->max('score') ?? 0 }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Best Score</div>
            </div>
        </div>
    @else
        <div class="h-64 flex items-center justify-center p-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
            <div class="text-center text-gray-500 dark:text-gray-400">
                <div class="text-6xl mb-4">📊</div>
                <p class="text-lg">No diary entries found in the last 30 days</p>
                <p class="text-sm mt-2">Start creating diary entries to see your performance!</p>
                <a href="{{ route('diaries.create') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Create Diary Entry
                </a>
            </div>
        </div>
    @endif
</div>
