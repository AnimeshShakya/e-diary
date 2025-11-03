<div class="space-y-6 animate-fade-in-up">
    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <div class="p-6">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-400">{{ session('status') }}</div>
            @endif

            @if ($affirmation)
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-400">Type</div>
                        <div class="text-lg font-medium">{{ $affirmation->type ? 'Morning' : 'Night' }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('affirmations.edit', $affirmation) }}" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm">Edit</a>
                        <button wire:click="delete({{ $affirmation->id }})" class="px-3 py-1.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm">Delete</button>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-400 mb-1">Description</div>
                    <div class="whitespace-pre-line text-gray-200">{{ $affirmation->description }}</div>
                </div>
            @else
                <div class="text-gray-300">You don't have an affirmation yet.</div>
                <div class="mt-4">
                    <a href="{{ route('affirmations.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">Create Affirmation</a>
                </div>
            @endif
        </div>
    </div>
</div>
