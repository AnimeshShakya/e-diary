<div class="space-y-4 sm:space-y-6 animate-fade-in-up">
    <div class="flex flex-col gap-3 sm:gap-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-wrap">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search name or email..."
                class="w-full sm:w-64 rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
            />
            <input
                type="text"
                wire:model.live.debounce.300ms="searchEmail"
                placeholder="Filter by email..."
                class="w-full sm:w-48 rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
            />
            <select
                wire:model.live="searchRole"
                class="w-full sm:w-auto rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">All Roles</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
            </select>
            <select
                wire:model.live="searchStatus"
                class="w-full sm:w-auto rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium shadow-lg shadow-indigo-600/20 text-center">
                New User
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800/60">
                <thead class="bg-gray-900/40">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400">Name</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden md:table-cell">Email</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden sm:table-cell">Role</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden lg:table-cell">Status</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-400 hidden lg:table-cell">Joined</th>
                        <th class="px-3 sm:px-4 py-3 text-right text-xs font-semibold text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-900/40">
                            <td class="px-3 sm:px-4 py-3 text-sm">
                                <div class="font-medium">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400 md:hidden">{{ $user->email }}</div>
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-gray-300 text-sm hidden md:table-cell">{{ $user->email }}</td>
                            <td class="px-3 sm:px-4 py-3 hidden sm:table-cell"><span class="px-2 py-1 text-xs rounded bg-gray-800/60 whitespace-nowrap">{{ $user->role ?? 'user' }}</span></td>
                            <td class="px-3 sm:px-4 py-3 hidden lg:table-cell">
                                @if($user->is_active)
                                    <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400 border border-green-500/30 whitespace-nowrap">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-700/50 text-gray-400 border border-gray-600 whitespace-nowrap">Inactive</span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-gray-400 text-sm hidden lg:table-cell">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-3 sm:px-4 py-3">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-2 sm:px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs sm:text-sm">Edit</a>
                                    <button x-on:click.prevent="if (confirm('Are you sure you want to delete this user?')) { $wire.delete({{ $user->id }}) }" class="px-2 sm:px-3 py-1.5 rounded-lg bg-red-600/80 hover:bg-red-600 text-white text-xs sm:text-sm">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 sm:px-4 py-6 text-center text-gray-400 text-sm">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 sm:px-4 py-3 border-t border-gray-800/60">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: @entangle('showModal') }">
        <div
            x-cloak
            x-show="open"
            class="fixed inset-0 z-[60] flex items-center justify-center"
        >
            <div class="absolute inset-0 bg-black/60" @click="open = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl border border-gray-800/60 bg-[#111111] p-6 shadow-2xl">
                <h2 class="text-lg font-semibold mb-4">Edit User</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Name</label>
                        <input type="text" wire:model.defer="form.name" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('form.name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Email</label>
                        <input type="email" wire:model.defer="form.email" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('form.email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Password {{ $editId ? '(leave blank to keep current)' : '' }}</label>
                        <input type="password" wire:model.defer="form.password" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('form.password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Role</label>
                        <select wire:model.defer="form.role" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                        @error('form.role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
