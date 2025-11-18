<?php

namespace App\Livewire\Diaries;

use App\Models\Checklist;
use App\Models\DailyScore;
use App\Models\Diary;
use App\Services\ScoreService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public Diary $diary;

    /** @var array{date:?string,yesterday_sleep_time:?string,today_wake_time:?string,text:?string,urgent_important:?string,urgent_notimportant:?string,noturgent_important:?string,noturgent_notimportant:?string,good_memo:?string,sleep:?int,management:?int,utilized:?int,wasted:?int,checklist_array:?string} */
    public array $form = [];

    /** @var int[] */
    public array $selectedChecklists = [];

    public function mount(Diary $diary): void
    {
        if ($diary->user_id !== Auth::id()) {
            abort(403);
        }

        $this->diary = $diary;
        $this->form = [
            'date' => optional($diary->date)->format('Y-m-d'),
            'yesterday_sleep_time' => $diary->yesterday_sleep_time,
            'today_wake_time' => $diary->today_wake_time,
            'text' => $diary->text,
            'urgent_important' => $diary->urgent_important,
            'urgent_notimportant' => $diary->urgent_notimportant,
            'noturgent_important' => $diary->noturgent_important,
            'noturgent_notimportant' => $diary->noturgent_notimportant,
            'good_memo' => $diary->good_memo,
            'sleep' => is_numeric($diary->sleep) ? ScoreService::minutesToTime($diary->sleep) : $diary->sleep,
            'management' => is_numeric($diary->management) ? ScoreService::minutesToTime($diary->management) : $diary->management,
            'utilized' => is_numeric($diary->utilized) ? ScoreService::minutesToTime($diary->utilized) : $diary->utilized,
            'wasted' => is_numeric($diary->wasted) ? ScoreService::minutesToTime($diary->wasted) : $diary->wasted,
            'checklist_array' => $diary->checklist_array,
        ];

        $decoded = json_decode((string) $diary->checklist_array, true);
        $this->selectedChecklists = is_array($decoded) ? array_values(array_filter($decoded, 'is_numeric')) : [];
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
            'form.sleep' => ['required', 'date_format:H:i'],
            'form.management' => ['required', 'date_format:H:i'],
            'form.utilized' => ['required', 'date_format:H:i'],
            'form.wasted' => ['required', 'date_format:H:i'],
            'form.checklist_array' => ['required', 'string'],
        ];
    }

    public function save(): mixed
    {
        $this->syncChecklistArray();
        $validated = $this->validate();

        // Convert HH:MM to minutes for storage
        $data = $validated['form'];
        $data['sleep'] = ScoreService::timeToMinutes($data['sleep']);
        $data['management'] = ScoreService::timeToMinutes($data['management']);
        $data['utilized'] = ScoreService::timeToMinutes($data['utilized']);
        $data['wasted'] = ScoreService::timeToMinutes($data['wasted']);

        $this->diary->update($data);
        // update stored score for this diary
        try {
            $score = ScoreService::calculateFromDiary($this->diary->fresh());
            DailyScore::updateOrCreate([
                'user_id' => $this->diary->user_id,
                'date' => $this->diary->date,
            ], ['score' => $score]);
        } catch (\Throwable $e) {
            // don't block diary update if score persist fails
        }

        session()->flash('status', 'Diary updated');

        return redirect()->route('diaries.index');
    }

    public function render(): View
    {
        return view('livewire.diaries.edit', [
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
            'title' => 'Edit Diary',
            'header' => 'Edit Diary',
        ];
    }
}
