<?php

namespace App\Livewire\Admin\Diaries;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use App\Models\Diary;
use App\Models\User;
use Illuminate\Contracts\View\View;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public Diary $diary;

    /**
     * @var array{
     *  user_id:int|null,
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
    public array $form = [];

    public function mount(Diary $diary): void
    {
        $this->diary = $diary;
        $this->form = [
            'user_id' => $diary->user_id,
            'date' => optional($diary->date)->format('Y-m-d'),
            'yesterday_sleep_time' => $diary->yesterday_sleep_time,
            'today_wake_time' => $diary->today_wake_time,
            'text' => $diary->text,
            'urgent_important' => $diary->urgent_important,
            'urgent_notimportant' => $diary->urgent_notimportant,
            'noturgent_important' => $diary->noturgent_important,
            'noturgent_notimportant' => $diary->noturgent_notimportant,
            'good_memo' => $diary->good_memo,
            'sleep' => $diary->sleep,
            'management' => $diary->management,
            'utilized' => $diary->utilized,
            'wasted' => $diary->wasted,
            'checklist_array' => $diary->checklist_array,
        ];
    }

    protected function rules(): array
    {
        return [
            'form.user_id' => ['required', 'integer', Rule::exists('users', 'id')],
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
        $this->validate();
        $this->diary->update($this->form);

        session()->flash('status', 'Diary updated');

        return redirect()->route('admin.diaries.index');
    }

    public function render(): View
    {
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.diaries.edit', [
            'users' => $users,
        ]);
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Edit Diary',
            'header' => 'Edit Diary',
        ];
    }
}
