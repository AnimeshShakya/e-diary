<div class="space-y-6 animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live="search" placeholder="Search checklists..." class="rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <a href="{{ route('admin.checklists.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">New Checklist</a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($checklists as $c)
                <tr class="border-t border-gray-800/60">
                    <td class="px-6 py-3">{{ $c->name }}</td>
                    <td class="px-6 py-3 text-gray-400">{{ $c->description }}</td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <a href="{{ route('admin.checklists.edit', $c) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700">Edit</a>
                        <button wire:click="delete({{ $c->id }})" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-500" onclick="return confirm('Delete this checklist?')">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-6 text-center text-gray-400">No checklists found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-800/60">{{ $checklists->links() }}</div>
    </div>
</div>
