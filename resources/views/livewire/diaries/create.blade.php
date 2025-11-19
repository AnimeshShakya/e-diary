<div class="space-y-6 animate-fade-in-up">
    <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/30">
        <div class="p-6">

            <!-- Main Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Date</label>
                    <input 
                        type="date" 
                        wire:model.live="form.date"
                        onfocus="this.showPicker()"
                        onclick="this.showPicker()" 
                        class="w-half rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('form.date') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Yesterday Sleep Time -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Yesterday Sleep Time</label>
                    <input 
                        type="time" 
                        wire:model.live="form.yesterday_sleep_time"
                        onclick="this.showPicker()" 
                        onfocus="this.showPicker()"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('form.yesterday_sleep_time') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Today Wake Time -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Today Wake Time</label>
                    <input 
                        type="time" 
                        wire:model.live="form.today_wake_time"
                        onclick="this.showPicker()" 
                        onfocus="this.showPicker()"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    @error('form.today_wake_time') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Text -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Text</label>
                    <textarea 
                        rows="3" 
                        wire:model.live="form.text"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.text') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Good Memo -->
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-1">Good Memo</label>
                    <textarea 
                        rows="2" 
                        wire:model.live="form.good_memo"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.good_memo') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Sleep -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Sleep</label>
                    <input 
                        type="text"
                        wire:model.live="form.sleep"
                        readonly
                        class="time-input w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200"
                    />
                    @error('form.sleep') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Management -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Management</label>
                    <input 
                        type="text"
                        placeholder="HH:MM"
                        maxlength="5"
                        x-data="{}"
                        x-on:input="formatTimeInput($event.target)"
                        wire:model.live="form.management"
                        class="time-input w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200"
                    />
                    @error('form.management') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Utilized -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Utilized</label>
                    <input 
                        type="text"
                        placeholder="HH:MM"
                        maxlength="5"
                        x-data="{}"
                        x-on:input="formatTimeInput($event.target)"
                        wire:model.live="form.utilized"
                        class="time-input w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200"
                    />
                    @error('form.utilized') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Wasted -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Wasted</label>
                    <input 
                        type="text"
                        wire:model.live="form.wasted"
                        readonly
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200"
                    />
                    @error('form.wasted') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

            </div>

            <!-- Checklist Section -->
            <div class="md:col-span-2 mt-6">
                <label class="block text-sm text-gray-400 mb-1">Checklist</label>

                <div class="flex flex-wrap gap-2">
                    @forelse($checklists as $c)
                        <button 
                            type="button" 
                            wire:click="toggleChecklist({{ $c->id }})"
                            class="px-3 py-1.5 rounded-full border text-sm transition
                                {{ in_array($c->id, $selectedChecklists ?? [])
                                    ? 'bg-indigo-600 border-indigo-500 text-white'
                                    : 'bg-gray-900/40 border-gray-800 text-gray-300 hover:bg-gray-800/60' }}">
                            {{ $c->name }}
                        </button>
                    @empty
                        <div class="text-sm text-gray-500">
                            No checklists yet. Admins can create them in Admin > Checklists.
                        </div>
                    @endforelse
                </div>

                <input type="hidden" wire:model.live="form.checklist_array" />
                @error('form.checklist_array') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- For Tomorrow Title -->
            <div class="md:col-span-2 mt-10">
                <span class="block text-xl text-gray-600 my-4">For Tomorrow</span>
            </div>

            <!-- For Tomorrow Matrix -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Not Urgent & Important</label>
                    <textarea rows="3" 
                        wire:model.live="form.noturgent_important"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.noturgent_important') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Urgent & Important</label>
                    <textarea rows="3" 
                        wire:model.live="form.urgent_important"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.urgent_important') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Urgent & Not Important</label>
                    <textarea rows="3" 
                        wire:model.live="form.urgent_notimportant"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.urgent_notimportant') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Not Urgent & Not Important</label>
                    <textarea rows="3" 
                        wire:model.live="form.noturgent_notimportant"
                        class="w-full rounded-xl border-gray-800 bg-gray-900/40 text-gray-200 
                               focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.noturgent_notimportant') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-800/60 flex items-center justify-end gap-3">
            <a 
                href="{{ route('diaries.index') }}" 
                class="px-4 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200"
            >Cancel</a>

            <button 
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                @disabled(!$this->isFormComplete)
                class="px-4 py-2 rounded-xl bg-indigo-600 text-white disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="save">Save</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>
</div>

<script>
function formatTimeInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value.length >= 3) {
        value = value.substring(0, 2) + ':' + value.substring(2, 4);
    }
    input.value = value;
    input.dispatchEvent(new Event('input', { bubbles: true }));
}
</script>
