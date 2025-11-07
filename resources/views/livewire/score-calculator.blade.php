<div class="max-w-4xl mx-auto p-6 bg-white dark:bg-[#1a1a1a] rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200">Daily Productivity Score Calculator</h2>

    <!-- Date Selector -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Date:</label>
        <input 
            type="date" 
            wire:model.live="selectedDate" 
            class="w-full md:w-auto border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 p-2 rounded-lg focus:ring-2 focus:ring-blue-500"
            max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
        >
    </div>

    @if($hasData)
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Left Column: Data Display -->
            <div>
                <!-- Checklist Section -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3 text-blue-800 dark:text-blue-400">📋 Checklist (50%)</h3>
                    
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Checklist Items:</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalChecklist }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed Items:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $completedChecklist }}</span>
                        </div>
                    </div>
                    
                    @if($totalChecklist > 0)
                        <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Completion Rate</div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ round(($completedChecklist / $totalChecklist) * 100, 1) }}%
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                <div 
                                    class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ ($completedChecklist / $totalChecklist) * 100 }}%"
                                ></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Time Management Section -->
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3 text-green-800 dark:text-green-400">⏰ Time Management (50%)</h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-white dark:bg-gray-800 rounded">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sleep (hrs)</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sleep }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Ideal: 6-8 hrs</div>
                        </div>

                        <div class="p-3 bg-white dark:bg-gray-800 rounded">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Management (hrs)</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $management }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Less is better</div>
                        </div>

                        <div class="p-3 bg-white dark:bg-gray-800 rounded">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Utilized (hrs)</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $utilized }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">More is better</div>
                        </div>

                        <div class="p-3 bg-white dark:bg-gray-800 rounded">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Wasted (hrs)</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $wasted }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Less is better</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Results -->
            <div>
                @if($score > 0)
                    <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border-2 border-purple-200 dark:border-purple-700">
                        <!-- Final Score -->
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Your Daily Score</h3>
                            <div class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                {{ $score }}
                            </div>
                            <div class="text-2xl text-gray-600 dark:text-gray-400">out of 10</div>
                        </div>

                        <!-- Score Rating -->
                        <div class="mb-6 p-4 rounded-lg text-center
                            @if($score >= 9) bg-green-100 dark:bg-green-900/30 border-2 border-green-400
                            @elseif($score >= 7) bg-blue-100 dark:bg-blue-900/30 border-2 border-blue-400
                            @elseif($score >= 5) bg-yellow-100 dark:bg-yellow-900/30 border-2 border-yellow-400
                            @else bg-red-100 dark:bg-red-900/30 border-2 border-red-400
                            @endif
                        ">
                            <div class="text-3xl mb-2">
                                @if($score >= 9) 
                                @elseif($score >= 7)
                                @elseif($score >= 5)
                                @else
                                @endif
                            </div>
                            <div class="font-semibold text-lg dark:text-gray-200">
                                @if($score >= 9) Excellent!
                                @elseif($score >= 7) Good Job!
                                @elseif($score >= 5) Fair
                                @else Needs Improvement
                                @endif
                            </div>
                        </div>

                        <!-- Score Breakdown -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Score Breakdown:</h4>
                            
                            <!-- Checklist Score -->
                            @php
                                $checklistScore = $totalChecklist > 0 ? ($completedChecklist / $totalChecklist) * 5 : 0;
                            @endphp
                            <div class="flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded">
                                <span class="text-sm dark:text-gray-300">📋 Checklist</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ round($checklistScore, 2) }} / 5</span>
                            </div>

                            <!-- Time Management Score -->
                            @php
                                if ($sleep >= 6 && $sleep <= 8) $sleepScore = 10;
                                elseif ($sleep == 5 || $sleep == 9) $sleepScore = 7;
                                elseif ($sleep == 4 || $sleep == 10) $sleepScore = 4;
                                else $sleepScore = 1;
                                $sleepFinal = ($sleepScore / 10) * 2.5;
                                
                                $managementScore = max(0, 10 - ($management - 1));
                                $managementFinal = ($managementScore / 10) * 1.5;
                                
                                $utilizedScore = min(($utilized / 16) * 10, 10);
                                $utilizedFinal = ($utilizedScore / 10) * 2;
                                
                                $wastedScore = max(0, 10 - $wasted);
                                $wastedFinal = ($wastedScore / 10) * 1;
                                
                                $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;
                            @endphp

                            <div class="flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded">
                                <span class="text-sm dark:text-gray-300">Sleep</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ round($sleepFinal, 2) }} / 2.5</span>
                            </div>

                            <div class="flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded">
                                <span class="text-sm dark:text-gray-300">Management</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ round($managementFinal, 2) }} / 1.5</span>
                            </div>

                            <div class="flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded">
                                <span class="text-sm dark:text-gray-300">Utilized Time</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ round($utilizedFinal, 2) }} / 2</span>
                            </div>

                            <div class="flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded">
                                <span class="text-sm dark:text-gray-300">Wasted Time</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ round($wastedFinal, 2) }} / 1</span>
                            </div>

                            <div class="border-t-2 border-gray-300 dark:border-gray-600 pt-2 mt-2">
                                <div class="flex justify-between items-center p-2 bg-purple-100 dark:bg-purple-900/30 rounded font-semibold">
                                    <span class="dark:text-gray-200">Time Score</span>
                                    <span class="text-purple-600 dark:text-purple-400">{{ round($timeScore, 2) }} / 5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-6">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                <div 
                                    class="h-4 rounded-full transition-all duration-500
                                    @if($score >= 9) bg-green-500
                                    @elseif($score >= 7) bg-blue-500
                                    @elseif($score >= 5) bg-yellow-500
                                    @else bg-red-500
                                    @endif"
                                    style="width: {{ ($score / 10) * 100 }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="h-96 flex items-center justify-center p-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
            <div class="text-center text-gray-500 dark:text-gray-400">
                <div class="text-6xl mb-4">📊</div>
                <p class="text-lg">No diary entry found for</p>
                <p class="font-semibold text-xl my-2 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</p>
                <p class="text-sm mt-2">Please select a date with a diary entry</p>
                <a href="{{ route('diaries.create') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Create Diary Entry
                </a>
            </div>
        </div>
    @endif
</div>
