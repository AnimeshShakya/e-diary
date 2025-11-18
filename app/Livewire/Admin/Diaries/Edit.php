<?php

namespace App\Livewire\Admin\Diaries;

use App\Models\DailyScore;
use App\Models\Diary;
use App\Models\User;
use App\Services\ScoreService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public Diary $diary;

    /** @var array{user_id:?int,date:?string,yesterday_sleep_time:?string,today_wake_time:?string,text:?string,urgent_important:?string,urgent_notimportant:?string,noturgent_important:?string,noturgent_notimportant:?string,good_memo:?string,sleep:?int,management:?int,utilized:?int,wasted:?int,checklist_array:?string} */
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
            'sleep' => is_numeric($diary->sleep) ? ScoreService::minutesToTime($diary->sleep) : $diary->sleep,
            'management' => is_numeric($diary->management) ? ScoreService::minutesToTime($diary->management) : $diary->management,
            'utilized' => is_numeric($diary->utilized) ? ScoreService::minutesToTime($diary->utilized) : $diary->utilized,
            'wasted' => is_numeric($diary->wasted) ? ScoreService::minutesToTime($diary->wasted) : $diary->wasted,
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
            'form.sleep' => ['required', 'date_format:H:i'],
            'form.management' => ['required', 'date_format:H:i'],
            'form.utilized' => ['required', 'date_format:H:i'],
            'form.wasted' => ['required', 'date_format:H:i'],
            'form.checklist_array' => ['required', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.sleep' => 'sleep',
            'form.management' => 'management',
            'form.utilized' => 'utilized',
            'form.wasted' => 'wasted',
        ];
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['form.sleep', 'form.management', 'form.utilized'])) {
            $this->recalculateHours();
        }
    }

    public function recalculateHours(): void
    {
        $sleepM = ScoreService::timeToMinutes($this->form['sleep'] ?? '00:00');
        $managementM = ScoreService::timeToMinutes($this->form['management'] ?? '00:00');
        $utilizedM = ScoreService::timeToMinutes($this->form['utilized'] ?? '00:00');

        $wastedM = 24 * 60 - ($sleepM + $managementM + $utilizedM);
        $wastedM = max(0, $wastedM);

        $this->form['wasted'] = ScoreService::minutesToTime($wastedM);

        $this->validateTotalHours();
    }

    public function validateTotalHours(): void
    {
        $sleepM = ScoreService::timeToMinutes($this->form['sleep'] ?? '00:00');
        $managementM = ScoreService::timeToMinutes($this->form['management'] ?? '00:00');
        $utilizedM = ScoreService::timeToMinutes($this->form['utilized'] ?? '00:00');
        $total = $sleepM + $managementM + $utilizedM;

        if ($total > 24 * 60) {
            $allowed = ScoreService::minutesToTime(24 * 60 - $sleepM);
            $this->addError('form.management', "Management hours cannot exceed {$allowed} based on sleep & utilized.");

            return;
        }

        $this->resetValidation('form.management');
        $this->resetValidation('form.utilized');
    }

    public function calculateSleep(): void
    {
        if (empty($this->form['yesterday_sleep_time']) || empty($this->form['today_wake_time'])) {
            return;
        }

        $yesterday = Carbon::parse($this->form['date'])->subDay()->setTimeFromTimeString($this->form['yesterday_sleep_time']);
        $today = Carbon::parse($this->form['date'])->setTimeFromTimeString($this->form['today_wake_time']);

        $diffMinutes = $yesterday->diffInMinutes($today);
        $this->form['sleep'] = ScoreService::minutesToTime($diffMinutes);

        $this->recalculateHours();
    }

    public function updatedFormYesterdaySleepTime(): void
    {
        $this->calculateSleep();
    }

    public function updatedFormTodayWakeTime(): void
    {
        $this->calculateSleep();
    }

    public function save(): \Illuminate\Http\RedirectResponse
    {
        $validated = $this->validate();
        $data = $validated['form'];

        // Convert HH:MM to minutes for storage
        $data['sleep'] = ScoreService::timeToMinutes($data['sleep']);
        $data['management'] = ScoreService::timeToMinutes($data['management']);
        $data['utilized'] = ScoreService::timeToMinutes($data['utilized']);
        $data['wasted'] = ScoreService::timeToMinutes($data['wasted']);

        $this->diary->update($data);

        // Update stored score for this diary
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
