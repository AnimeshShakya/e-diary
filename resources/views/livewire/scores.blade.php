<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-200">Daily Scores</h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('diaries.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Create Diary</a>
            <button type="button" onclick="window.location='{{ route('scores') }}?export=1'" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">Export CSV</button>
        </div>
    </div>

    <!-- Date Filters -->
    <div class="flex gap-3 items-end">
        <div>
            <label class="text-sm text-gray-400 block">Start date</label>
            <input wire:model.defer="start_date" type="date" class="mt-1 px-3 py-2 rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div>
            <label class="text-sm text-gray-400 block">End date</label>
            <input wire:model.defer="end_date" type="date" class="mt-1 px-3 py-2 rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div>
            <button wire:click="$refresh" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">Apply</button>
        </div>
    </div>

    <!-- Content -->
    @if(isset($scores) && $scores->count() > 0)
        <div class="bg-white/5 rounded-xl border border-gray-800 divide-y divide-gray-800">
            @foreach($scores as $day)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-gray-200">{{ 
                            \Carbon\Carbon::parse($day->date)->format('F d, Y')
                        }}</div>
                        <div class="text-sm text-gray-400">{{ $day->date }}</div>
                    </div>

                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-200">{{ number_format($day->score, 2) }}</div>
                        <div class="text-sm text-gray-400">Out of 10</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $scores->links() }}
        </div>
    @else
        <div class="bg-white/5 rounded-xl border border-gray-800 p-12 text-center">
            <div class="text-6xl mb-4">📊</div>
            <p class="text-gray-400 mb-4">No scores found for the selected range.</p>
            <a href="{{ route('diaries.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium">Create your first diary</a>
        </div>
    @endif
</div>
