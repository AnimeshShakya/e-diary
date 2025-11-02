<div class="space-y-6 animate-fade-in-up">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search diaries..."
                class="w-72 rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.diaries.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium shadow-lg shadow-indigo-600/20">
                New Diary
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <table class="min-w-full divide-y divide-gray-800/60">
            <thead class="bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400">Sleep (yesterday → today)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400">Score</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/60">
                @forelse($diaries as $diary)
                    <tr class="hover:bg-gray-900/40">
                        <td class="px-4 py-3">{{ $diary->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ \Illuminate\Support\Carbon::parse($diary->date)->toFormattedDateString() }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ $diary->yesterday_sleep_time }} → {{ $diary->today_wake_time }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 text-xs">
                                <span class="px-2 py-1 rounded bg-gray-800/60">Sleep {{ $diary->sleep }}</span>
                                <span class="px-2 py-1 rounded bg-gray-800/60">Mgmt {{ $diary->management }}</span>
                                <span class="px-2 py-1 rounded bg-gray-800/60">Util {{ $diary->utilized }}</span>
                                <span class="px-2 py-1 rounded bg-gray-800/60">Waste {{ $diary->wasted }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.diaries.edit', $diary) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-200 text-sm">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">No diaries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-800/60">
            {{ $diaries->links() }}
        </div>
    </div>

    <!-- Modal removed: editing now happens on dedicated page -->
</div>
