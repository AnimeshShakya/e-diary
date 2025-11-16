<div class="max-w-7xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-200">Create Affirmations</h2>
            <p class="text-sm text-gray-400 mt-1">Set your morning and night affirmations to stay motivated</p>
        </div>
        <a href="{{ route('affirmations.index') }}" class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200 text-sm">
            Back
        </a>
    </div>

    <!-- Content -->
    <div class="bg-white/5 rounded-xl border border-gray-800">
        <div class="p-6">

            <div class="space-y-6">
                <!-- Morning Affirmation -->
                <div class="p-4 rounded-xl bg-yellow-900/10 border border-yellow-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <label class="text-sm font-semibold text-yellow-400">Morning Affirmation</label>
                    </div>
                    <textarea rows="4" wire:model.defer="form.morning_affirmation" 
                        placeholder="e.g., Today is going to be a great day. I am confident, capable, and ready to achieve my goals."
                        class="w-full rounded-xl border-gray-700 bg-gray-900/60 text-gray-200 placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500"></textarea>
                    @error('form.morning_affirmation') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Night Affirmation -->
                <div class="p-4 rounded-xl bg-indigo-900/10 border border-indigo-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <label class="text-sm font-semibold text-indigo-400">Night Affirmation</label>
                    </div>
                    <textarea rows="4" wire:model.defer="form.night_affirmation"
                        placeholder="e.g., I am grateful for today's experiences. I release all stress and embrace peaceful rest."
                        class="w-full rounded-xl border-gray-700 bg-gray-900/60 text-gray-200 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.night_affirmation') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            @if (session('status'))
                <div class="mt-4 text-sm text-green-400">{{ session('status') }}</div>
            @endif
        </div>
        <div class="px-6 py-4 border-t border-gray-800 flex items-center justify-end gap-3">
            <a href="{{ route('affirmations.index') }}" class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200">Cancel</a>
            <button wire:click="save" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium">Save Affirmations</button>
        </div>
    </div>
</div>
