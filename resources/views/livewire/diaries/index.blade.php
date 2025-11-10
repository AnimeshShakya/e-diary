<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    {{-- Show score calculator after creating diary --}}
    @if(session('show_score') && session('diary_date'))
        <div class="bg-white/5 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-200">🎉 Diary Created Successfully!</h3>
                <button onclick="this.closest('div').parentElement.style.display='none'" class="text-gray-400 hover:text-gray-200">✕</button>
            </div>
            @livewire('score-calculator', ['mode' => 'single', 'date' => session('diary_date')])
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-200">My Diaries</h2>
        <a href="{{ route('diaries.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">New Diary</a>
    </div>

    <!-- Search -->
    <div>
        <input
            type="text"
            placeholder="Search..."
            wire:model.live.debounce.300ms="search"
            class="w-full max-w-md rounded-lg border-gray-300 bg-white/5 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
        />
    </div>

    <!-- Content -->
    <div class="bg-white/5 rounded-xl border border-gray-800 divide-y divide-gray-800">
        @forelse ($diaries as $diary)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($diary->date)->toFormattedDateString() }}</div>
                    <div class="font-medium text-gray-200">{{ \Illuminate\Support\Str::limit($diary->text, 120) }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('diaries.edit', $diary) }}" class="px-3 py-1.5 text-sm rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-200">Edit</a>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">📔</div>
                <p class="text-gray-400 mb-4">No diaries yet.</p>
                <a href="{{ route('diaries.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium">Create your first diary</a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $diaries->links() }}
    </div>
</div>
