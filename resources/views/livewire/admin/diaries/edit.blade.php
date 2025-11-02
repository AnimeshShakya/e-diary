<div class="space-y-6 animate-fade-in-up">
    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">User</label>
                    <select wire:model.defer="form.user_id" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @error('form.user_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Checklist</label>
                    <textarea rows="2" wire:model.defer="form.checklist_array" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.checklist_array') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Yesterday Sleep Time</label>
                    <input type="time" wire:model.defer="form.yesterday_sleep_time" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.yesterday_sleep_time') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Today Wake Time</label>
                    <input type="time" wire:model.defer="form.today_wake_time" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.today_wake_time') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Text</label>
                    <textarea rows="3" wire:model.defer="form.text" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.text') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Urgent & Important</label>
                    <textarea rows="3" wire:model.defer="form.urgent_important" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.urgent_important') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Urgent & Not Important</label>
                    <textarea rows="3" wire:model.defer="form.urgent_notimportant" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.urgent_notimportant') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Not Urgent & Important</label>
                    <textarea rows="3" wire:model.defer="form.noturgent_important" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.noturgent_important') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Not Urgent & Not Important</label>
                    <textarea rows="3" wire:model.defer="form.noturgent_notimportant" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.noturgent_notimportant') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Good Memo</label>
                    <textarea rows="2" wire:model.defer="form.good_memo" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.good_memo') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Sleep</label>
                    <input type="number" wire:model.defer="form.sleep" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.sleep') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Management</label>
                    <input type="number" wire:model.defer="form.management" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.management') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Utilized</label>
                    <input type="number" wire:model.defer="form.utilized" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.utilized') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wasted</label>
                    <input type="number" wire:model.defer="form.wasted" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('form.wasted') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Checklist</label>
                    <textarea rows="2" wire:model.defer="form.checklist_array" class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('form.checklist_array') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-800/60 flex items-center justify-end gap-3">
            <a href="{{ route('admin.diaries.index') }}" class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200">Cancel</a>
            <button wire:click="save" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">Save</button>
        </div>
    </div>
</div>
