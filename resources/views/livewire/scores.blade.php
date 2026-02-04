<div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-200">Daily Scores</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <a href="{{ route('diaries.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-center text-sm sm:text-base">Create Diary</a>
            <button type="button" onclick="window.location='{{ route('scores') }}?export=1'" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700 text-sm sm:text-base">Export CSV</button>
        </div>
    </div>

    <!-- Date Filters -->
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
        <div class="flex-1">
            <label class="text-sm text-gray-400 block">Start date</label>
            <input wire:model.defer="start_date" type="date" class="mt-1 w-full px-3 py-2 rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div class="flex-1">
            <label class="text-sm text-gray-400 block">End date</label>
            <input wire:model.defer="end_date" type="date" class="mt-1 w-full px-3 py-2 rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div>
            <button wire:click="$refresh" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">Apply</button>
        </div>
    </div>

    <!-- Content -->
    @if(isset($scores) && $scores->count() > 0)
        <div class="bg-white/5 rounded-xl border border-gray-800 divide-y divide-gray-800">
            @foreach($scores as $day)
                <div class="p-3 sm:p-4 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-200 text-sm sm:text-base">{{ 
                            \Carbon\Carbon::parse($day->date)->format('F d, Y')
                        }}</div>
                        <div class="text-xs sm:text-sm text-gray-400">{{ $day->date }}</div>
                    </div>

                    <div class="text-right flex-shrink-0">
                        <div class="text-xl sm:text-2xl font-bold text-gray-200">{{ number_format($day->score, 2) }}</div>
                        <div class="text-xs sm:text-sm text-gray-400">Out of 10</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $scores->links() }}
        </div>
    @else
        <div class="bg-white/5 rounded-xl border border-gray-800 p-8 sm:p-12 text-center">
            <div class="text-4xl sm:text-6xl mb-4">📊</div>
            <p class="text-gray-400 mb-4 text-sm sm:text-base">No scores found for the selected range.</p>
            <a href="{{ route('diaries.create') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm sm:text-base">Create your first diary</a>
        </div>
    @endif
</div>
