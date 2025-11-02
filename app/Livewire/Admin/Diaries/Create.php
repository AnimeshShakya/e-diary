<?php

namespace App\Livewire\Admin\Diaries;

use App\Models\Diary;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.admin')]
class Create extends Component
{
    /**
     * @var array{
     *  date:string|null,
     *  yesterday_sleep_time:string|null,
     *  today_wake_time:string|null,
     *  text:string|null,
     *  urgent_important:string|null,
     *  urgent_notimportant:string|null,
     *  noturgent_important:string|null,
     *  noturgent_notimportant:string|null,
     *  good_memo:string|null,
     *  sleep:int|null,
     *  management:int|null,
     *  utilized:int|null,
     *  wasted:int|null,
     *  checklist_array:string|null,
     * }
     */
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

    public function save(): \Illuminate\Http\RedirectResponse
    {
        $validated = $this->validate();
        $data = $validated['form'];
    $data['user_id'] = Auth::id();

        Diary::create($data);

        session()->flash('status', 'Diary created');

        return redirect()->route('admin.diaries.index');
    }

    public function render(): View
    {
        return view('livewire.admin.diaries.create');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'New Diary',
            'header' => 'Create Diary',
        ];
    }
}
