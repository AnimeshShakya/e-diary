<div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
    {{-- Show score calculator after creating diary --}}
    @if(session('show_score') && session('diary_date'))
        <div class="bg-white/5 rounded-xl border border-gray-800 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-200">🎉 Diary Created Successfully!</h3>
                <button onclick="this.closest('div').parentElement.style.display='none'" class="text-gray-400 hover:text-gray-200 flex-shrink-0">✕</button>
            </div>
            @livewire('score-calculator', ['mode' => 'single', 'date' => session('diary_date')])
        </div>
    @endif

    <div class="space-y-4 sm:space-y-6 animate-fade-in-up">
        <div class="flex flex-col gap-3 sm:gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-wrap">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search diaries..."
                    class="w-full sm:w-64 rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                />
                <input
                    type="date"
                    wire:model.live="searchDate"
                    placeholder="Filter by date..."
                    class="w-full sm:w-auto rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('diaries.create') }}" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium shadow-lg shadow-indigo-600/20 text-center">
                    New Diary
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800/60">
                    <thead class="bg-gray-900/40">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400">Date</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden md:table-cell">Sleep (yesterday → today)</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden lg:table-cell">Score</th>
                            <th class="px-3 sm:px-4 py-3 text-right text-xs font-semibold text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($diaries as $diary)
                            <tr class="hover:bg-gray-900/40">
                                <td class="px-3 sm:px-4 py-3 text-gray-300 text-sm">{{ \Illuminate\Support\Carbon::parse($diary->date)->format('M d, Y') }}</td>
                                <td class="px-3 sm:px-4 py-3 text-gray-300 text-sm hidden md:table-cell">{{ $diary->yesterday_sleep_time }} → {{ $diary->today_wake_time }}</td>
                                <td class="px-3 sm:px-4 py-3 hidden lg:table-cell">
                                    <div class="flex flex-wrap gap-1 sm:gap-2 text-xs">
                                        <span class="px-2 py-1 rounded bg-gray-800/60 whitespace-nowrap">Sleep {{ $diary->sleep }}</span>
                                        <span class="px-2 py-1 rounded bg-gray-800/60 whitespace-nowrap">Mgmt {{ $diary->management }}</span>
                                        <span class="px-2 py-1 rounded bg-gray-800/60 whitespace-nowrap">Util {{ $diary->utilized }}</span>
                                        <span class="px-2 py-1 rounded bg-gray-800/60 whitespace-nowrap">Waste {{ $diary->wasted }}</span>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-4 py-3">
                                    <div class="flex items-center justify-end gap-1 sm:gap-2">
                                        <button wire:click="view({{ $diary->id }})" class="px-2 sm:px-3 py-1.5 rounded-lg bg-blue-600/80 hover:bg-blue-600 text-white text-xs sm:text-sm">Show</button>
                                        <a href="{{ route('diaries.edit', $diary) }}" class="px-2 sm:px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs sm:text-sm">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 sm:px-4 py-12 text-center">
                                    <div class="text-4xl sm:text-6xl mb-4">📔</div>
                                    <p class="text-gray-400 mb-4 text-sm sm:text-base">No diaries yet.</p>
                                    <a href="{{ route('diaries.create') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm sm:text-base">Create your first diary</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 sm:px-4 py-3 border-t border-gray-800/60">
                {{ $diaries->links() }}
            </div>
        </div>
    </div>

    <!-- View Modal -->
    @if($viewDiary)
        <div x-data="{ open: @entangle('showViewModal') }">
            <div
                x-cloak
                x-show="open"
                class="fixed inset-0 z-[60] flex items-center justify-center overflow-y-auto p-4"
            >
                <div class="absolute inset-0 bg-black/60" @click="open = false"></div>
                <div class="relative w-full max-w-3xl rounded-2xl border border-gray-800/60 bg-[#111111] p-4 sm:p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-200">View Diary</h2>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-200">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Date</label>
                                <p class="text-gray-200">{{ \Illuminate\Support\Carbon::parse($viewDiary->date)->toFormattedDateString() }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Yesterday Sleep Time</label>
                                <p class="text-gray-200">{{ $viewDiary->yesterday_sleep_time }}</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Today Wake Time</label>
                                <p class="text-gray-200">{{ $viewDiary->today_wake_time }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Diary Text</label>
                            <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->text }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Urgent & Important</label>
                                <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->urgent_important }}</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Urgent & Not Important</label>
                                <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->urgent_notimportant }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Not Urgent & Important</label>
                                <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->noturgent_important }}</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Not Urgent & Not Important</label>
                                <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->noturgent_notimportant }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Good Memo</label>
                            <p class="text-gray-200 whitespace-pre-wrap text-sm sm:text-base">{{ $viewDiary->good_memo }}</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm text-gray-400 mb-1">Sleep Hours</label>
                                <p class="text-gray-200 text-sm sm:text-base">{{ $viewDiary->sleep }}</p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm text-gray-400 mb-1">Management Hours</label>
                                <p class="text-gray-200 text-sm sm:text-base">{{ $viewDiary->management }}</p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm text-gray-400 mb-1">Utilized Hours</label>
                                <p class="text-gray-200 text-sm sm:text-base">{{ $viewDiary->utilized }}</p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm text-gray-400 mb-1">Wasted Hours</label>
                                <p class="text-gray-200 text-sm sm:text-base">{{ $viewDiary->wasted }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Checklists</label>
                            <p class="text-gray-200 text-sm sm:text-base">{{ $viewDiary->checklist_array }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button @click="open = false" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
