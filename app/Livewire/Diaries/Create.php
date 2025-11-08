<?php

namespace App\Livewire\Diaries;

use App\Models\Checklist;
use App\Models\Diary;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Create extends Component
{
    /** @var array{date:?string,yesterday_sleep_time:?string,today_wake_time:?string,text:?string,urgent_important:?string,urgent_notimportant:?string,noturgent_important:?string,noturgent_notimportant:?string,good_memo:?string,sleep:?int,management:?int,utilized:?int,wasted:?int,checklist_array:?string} */
    public array $form = [
        'date' => null,
        'yesterday_sleep_time' => null,
        'today_wake_time' => null,
        'text' => null,
        'urgent_important' => null,
        'urgent_notimportant' => null,
        'noturgent_important' => null,
        'noturgent_notimportant' => null,
        'good_memo' => null,
        'sleep' => null,
        'management' => null,
        'utilized' => null,
        'wasted' => null,
        'checklist_array' => null,
    ];

    /** @var int[] */
    public array $selectedChecklists = [];

    public function mount(): void
    {
        $this->selectedChecklists = [];
        $this->syncChecklistArray();
    }

    protected function rules(): array
    {
        return [
            'form.date' => ['required', 'date'],
            'form.yesterday_sleep_time' => ['required', 'date_format:H:i'],
            'form.today_wake_time' => ['required', 'date_format:H:i'],
            'form.text' => ['required', 'string'],
            'form.urgent_important' => ['required', 'string'],
            'form.urgent_notimportant' => ['required', 'string'],
            'form.noturgent_important' => ['required', 'string'],
            'form.noturgent_notimportant' => ['required', 'string'],
            'form.good_memo' => ['required', 'string'],
            'form.sleep' => ['required', 'integer', 'min:0', 'max:24'],
            'form.management' => ['required', 'integer', 'min:0', 'max:24'],
            'form.utilized' => ['required', 'integer', 'min:0', 'max:24'],
            'form.wasted' => ['required', 'integer', 'min:0', 'max:24'],
            'form.checklist_array' => ['required', 'string'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form.sleep' => 'sleep hours',
            'form.management' => 'management hours',
            'form.utilized' => 'utilized hours',
            'form.wasted' => 'wasted hours',
        ];
    }

    public function updated($propertyName): void
    {
        // Validate individual field
        $this->validateOnly($propertyName);

        // Check 24-hour total when any time field is updated
        if (in_array($propertyName, ['form.sleep', 'form.management', 'form.utilized', 'form.wasted'])) {
            $this->validateTotalHours();
        }
    }

    protected function validateTotalHours(): void
    {
        $total = ($this->form['sleep'] ?? 0) + ($this->form['management'] ?? 0) + ($this->form['utilized'] ?? 0) + ($this->form['wasted'] ?? 0);

        // If total is exactly 24, clear any previous errors for these fields.
        if ($total === 24) {
            $this->resetErrorBag([
                'form.sleep',
                'form.management',
                'form.utilized',
                'form.wasted',
            ]);

            return;
        }

        // Otherwise, set a validation error on the time fields so the user knows
        // the sum must equal 24 hours. We add the same message to each field so
        // they are highlighted together; these will be cleared when corrected.
        $message = 'The total of sleep, management, utilized, and wasted hours must equal 24 hours.';
        $this->addError('form.sleep', $message);
        $this->addError('form.management', $message);
        $this->addError('form.utilized', $message);
        $this->addError('form.wasted', $message);
    }

    protected function messages()
    {
        return [
            'form.date.required' => 'The date field is required.',
            'form.date.date' => 'Please enter a valid date.',
            'form.yesterday_sleep_time.required' => 'Yesterday sleep time is required.',
            'form.yesterday_sleep_time.date_format' => 'Time must be in HH:MM format.',
            'form.today_wake_time.required' => 'Today wake time is required.',
            'form.text.required' => 'Please enter your diary text.',
            'form.sleep.required' => 'Please enter your sleep hours.',
            'form.sleep.integer' => 'Sleep must be a number.',
            'form.management.required' => 'Please enter management hours.',
            'form.utilized.required' => 'Please enter utilized hours.',
            'form.wasted.required' => 'Please enter wasted hours.',
            'form.checklist_array.required' => 'Please select at least one checklist item.',
            'form.checklist_array.string' => 'Invalid checklist data.',
            'form.urgent_important.required' => 'This field is required.',
            'form.urgent_notimportant.required' => 'This field is required.',
            'form.noturgent_important.required' => 'This field is required.',
            'form.noturgent_notimportant.required' => 'This field is required.',
            'form.good_memo.required' => 'This field is required.',

        ];
    }

    public function save(): mixed
    {
        $this->syncChecklistArray();

        // Validate 24-hour total before saving
        $this->validateTotalHours();

        if ($this->getErrorBag()->isNotEmpty()) {
            return null;
        }

        $data = $this->validate()['form'];
        $data['user_id'] = Auth::id();
        $diary = Diary::create($data);

        session()->flash('status', 'Diary created successfully!');
        session()->flash('show_score', true);
        session()->flash('diary_date', $diary->date);

        return redirect()->route('diaries.index');
    }

    public function render(): View
    {
        return view('livewire.diaries.create', [
            'checklists' => Checklist::query()->orderBy('name')->get(),
        ]);
    }

    public function toggleChecklist(int $id): void
    {
        if (in_array($id, $this->selectedChecklists, true)) {
            $this->selectedChecklists = array_values(array_diff($this->selectedChecklists, [$id]));
        } else {
            $this->selectedChecklists[] = $id;
        }
        $this->syncChecklistArray();
    }

    private function syncChecklistArray(): void
    {
        $this->form['checklist_array'] = json_encode($this->selectedChecklists);
    }

    public function layoutData(): array
    {
        return [
            'title' => 'New Diary',
            'header' => 'Create Diary',
        ];
    }
}
