<?php

namespace App\Livewire\Admin\Diaries;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $searchDate = '';

    public int $perPage = 10;

    public bool $showModal = false;

    public bool $showViewModal = false;

    public ?int $editId = null;

    public ?int $viewId = null;

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
    public array $form = [
        'user_id' => null,
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSearchDate(): void
    {
        $this->resetPage();
    }

    public function view(Diary $diary): void
    {
        $this->viewId = $diary->id;
        $this->showViewModal = true;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editId = null;
        $this->showModal = true;
    }

    public function edit(Diary $diary): void
    {
        $this->editId = $diary->id;
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
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $payload = $this->form;
        // Normalize empty strings to null where appropriate
        $payload['date'] = $payload['date'] ?: null;

        if ($this->editId) {
            Diary::findOrFail($this->editId)->update($payload);
        } else {
            Diary::create($payload);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('notify', message: 'Diary saved');
    }

    public function delete(Diary $diary): void
    {
        $diary->delete();
        $this->dispatch('notify', message: 'Diary deleted');
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

    protected function resetForm(): void
    {
        $this->form = [
            'user_id' => null,
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
    }

    public function render(): View
    {
        $diaries = Diary::query()
            ->with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('text', 'like', "%{$this->search}%")
                        ->orWhere('good_memo', 'like', "%{$this->search}%");
                });
            })
            ->when($this->searchDate, function ($q) {
                $q->whereDate('date', $this->searchDate);
            })
            ->orderByDesc('date')
            ->paginate($this->perPage);

        $users = User::select('id', 'name')->orderBy('name')->get();

        $viewDiary = $this->viewId ? Diary::with('user')->find($this->viewId) : null;

        return view('livewire.admin.diaries.index', [
            'diaries' => $diaries,
            'users' => $users,
            'viewDiary' => $viewDiary,
        ]);
    }

    /**
     * Provide data to the admin layout.
     *
     * @return array{title:string,header:string}
     */
    public function layoutData(): array
    {
        return [
            'title' => 'Diaries',
            'header' => 'Manage Diaries',
        ];
    }
}
