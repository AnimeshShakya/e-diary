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
            'form.sleep' => ['required', 'integer'],
            'form.management' => ['required', 'integer'],
            'form.utilized' => ['required', 'integer'],
            'form.wasted' => ['required', 'integer'],
            'form.checklist_array' => ['required', 'string'],
        ];
    }

    public function save(): mixed
    {
        $this->syncChecklistArray();
        $data = $this->validate()['form'];
        $data['user_id'] = Auth::id();
        Diary::create($data);

        session()->flash('status', 'Diary created');

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
