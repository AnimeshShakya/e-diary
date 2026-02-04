<div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 sm:gap-3">
            @if ($timeOfDay === 'morning')
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-200">Good Morning! ☀️</h2>
                    <p class="text-xs sm:text-sm text-gray-400">Here's your morning affirmation</p>
                </div>
            @else
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-200">Good Night! 🌙</h2>
                    <p class="text-xs sm:text-sm text-gray-400">Here's your night affirmation</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white/5 rounded-xl border border-gray-800">
        <div class="p-4 sm:p-6">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-400">{{ session('status') }}</div>
            @endif

            @if ($affirmation)
                <!-- Current Affirmation Display -->
                <div class="mb-4 sm:mb-6 p-4 sm:p-6 rounded-xl bg-gradient-to-br from-purple-900/20 to-pink-900/20 border border-purple-500/20">
                    <div class="text-xs sm:text-sm uppercase tracking-wide text-purple-400 mb-2">
                        {{ $timeOfDay === 'morning' ? 'Morning' : 'Night' }} Affirmation
                    </div>
                    <div class="text-lg sm:text-xl text-gray-200 font-medium leading-relaxed whitespace-pre-line">
                        {{ $currentAffirmation ?: 'No affirmation set for this time.' }}
                    </div>
                </div>

                <!-- Both Affirmations Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="p-3 sm:p-4 rounded-xl bg-yellow-900/10 border border-yellow-500/20">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="text-xs sm:text-sm font-semibold text-yellow-400">Morning Affirmation</div>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-300 whitespace-pre-line">{{ $affirmation->morning_affirmation ?? 'Not set' }}</div>
                    </div>

                    <div class="p-3 sm:p-4 rounded-xl bg-indigo-900/10 border border-indigo-500/20">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <div class="text-xs sm:text-sm font-semibold text-indigo-400">Night Affirmation</div>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-300 whitespace-pre-line">{{ $affirmation->night_affirmation ?? 'Not set' }}</div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2">
                    <a href="{{ route('affirmations.edit', $affirmation) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm text-center">Edit Affirmations</a>
                    <button wire:click="delete({{ $affirmation->id }})" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm">Delete</button>
                </div>
            @else
                <div class="text-center py-8 sm:py-12">
                    <div class="text-4xl sm:text-6xl mb-4">✨</div>
                    <div class="text-gray-400 mb-2 text-sm sm:text-base">You don't have an affirmation yet.</div>
                    <p class="text-xs sm:text-sm text-gray-400 mb-4 sm:mb-6">Create your morning and night affirmations to stay motivated!</p>
                    <a href="{{ route('affirmations.create') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Affirmation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
