<x-admin-layout title="Overview">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 sm:p-6">
            <h3 class="text-gray-400 text-xs sm:text-sm">Total Users</h3>
            <p class="text-xl sm:text-2xl font-bold mt-2">{{ $totalUsers }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 sm:p-6">
            <h3 class="text-gray-400 text-xs sm:text-sm">Total Diaries</h3>
            <p class="text-xl sm:text-2xl font-bold mt-2">{{ $totalDiaries }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 sm:p-6 sm:col-span-2 md:col-span-1">
            <h3 class="text-gray-400 text-xs sm:text-sm">Flagged Entries</h3>
            <p class="text-xl sm:text-2xl font-bold mt-2">{{ $flagged }}</p>
        </div>
    </div>

    <div class="mt-6 sm:mt-10 bg-gray-900 border border-gray-800 rounded-xl p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Recent Activity</h3>
        @foreach($activities as $activity)
            <div class="flex flex-col sm:flex-row sm:justify-between text-xs sm:text-sm text-gray-400 py-2 border-b border-gray-800 gap-1 sm:gap-0">
                <span class="break-words">{{ $activity->description }}</span>
                <span class="text-gray-500 sm:text-gray-400 flex-shrink-0">{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        @endforeach
    </div>
</x-admin-layout>
