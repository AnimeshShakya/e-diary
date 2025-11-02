<x-admin-layout title="Overview">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-gray-400 text-sm">Total Users</h3>
            <p class="text-2xl font-bold mt-2">{{ $totalUsers }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-gray-400 text-sm">Total Diaries</h3>
            <p class="text-2xl font-bold mt-2">{{ $totalDiaries }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-gray-400 text-sm">Flagged Entries</h3>
            <p class="text-2xl font-bold mt-2">{{ $flagged }}</p>
        </div>
    </div>

    <div class="mt-10 bg-gray-900 border border-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        @foreach($activities as $activity)
            <div class="flex justify-between text-sm text-gray-400 py-2 border-b border-gray-800">
                <span>{{ $activity->description }}</span>
                <span>{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        @endforeach
    </div>
</x-admin-layout>
