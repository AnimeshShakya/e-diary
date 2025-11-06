<div class="max-w-5xl mx-auto space-y-6">
    {{-- Show score calculator after creating diary --}}
    @if(session('show_score') && session('diary_date'))
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-200">🎉 Diary Created Successfully!</h3>
                <button onclick="this.closest('div').parentElement.style.display='none'" class="text-gray-400 hover:text-gray-200">✕</button>
            </div>
            @livewire('score-calculator', ['mode' => 'single', 'date' => session('diary_date')])
        </div>
    @endif

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">My Diaries</h2>
        <a href="{{ route('diaries.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">New Diary</a>
    </div>

    <div>
        <input
            type="text"
            placeholder="Search..."
            wire:model.live.debounce.300ms="search"
            class="w-full max-w-md rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
        />
    </div>

    <div class="bg-white/5 rounded-xl border border-gray-800 divide-y divide-gray-800">
        @forelse ($diaries as $diary)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($diary->date)->toFormattedDateString() }}</div>
                    <div class="font-medium">{{ \Illuminate\Support\Str::limit($diary->text, 120) }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('diaries.edit', $diary) }}" class="px-3 py-1.5 text-sm rounded-md bg-gray-800 hover:bg-gray-700">Edit</a>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">No diaries yet.</div>
        @endforelse
    </div>

    <div>
        {{ $diaries->links() }}
    </div>
</div>
