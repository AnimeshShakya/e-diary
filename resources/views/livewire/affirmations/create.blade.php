<div class="space-y-6 animate-fade-in-up">
    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Type</label>
                    <select wire:model.defer="form.type" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1">Morning</option>
                        <option value="0">Night</option>
                    </select>
                    @error('form.type') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Description</label>
                    <textarea rows="4" wire:model.defer="form.description" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-800/60 flex items-center justify-end gap-3">
            <a href="{{ route('affirmations.index') }}" class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200">Cancel</a>
            <button wire:click="save" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">Save</button>
        </div>
    </div>
</div>
