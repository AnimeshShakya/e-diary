<?php

namespace App\Livewire\Diaries;

use App\Models\Checklist;
use App\Models\DailyScore;
use App\Models\Diary;
use App\Services\ScoreService;
use Carbon\Carbon;
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

    public array $selectedChecklists = [];

    public function mount(): void
    {
        $this->form['date'] = now()->toDateString();
        $this->selectedChecklists = [];
        $this->syncChecklistArray();
        $this->get_previous_time_management_matrix();
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
    
    protected function validationAttributes(): array
    {
        return [
            'form.sleep' => 'sleep hours',
            'form.management' => 'management hours',
            'form.utilized' => 'utilized hours',
            'form.wasted' => 'wasted hours',
        ];
    }
    
    public function get_previous_time_management_matrix(): void
    {
        $previousDiary = Diary::where('user_id', Auth::id())
            ->where('date', '<', $this->form['date'])
            ->orderBy('date', 'desc')
            ->first();
        $this->form['urgent_important'] = $previousDiary->urgent_important ?? null;
        $this->form['urgent_notimportant'] = $previousDiary->urgent_notimportant ?? null;
        $this->form['noturgent_important'] = $previousDiary->noturgent_important ?? null;
        $this->form['noturgent_notimportant'] = $previousDiary->noturgent_notimportant ?? null;
    }
    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['form.sleep', 'form.management', 'form.utilized', 'form.wasted'])) {
            $this->recalculateHours();
        } else {
            $this->validateOnly($propertyName);
        }
    }

    public function recalculateHours(): void
    {
        $this->resetValidation([
            'form.sleep',
            'form.management',
            'form.utilized',
            'form.wasted',
        ]);

        $sleepStr = trim($this->form['sleep'] ?? '00:00') ?: '00:00';
        $managementStr = trim($this->form['management'] ?? '00:00') ?: '00:00';
        $utilizedStr = trim($this->form['utilized'] ?? '00:00') ?: '00:00';

        $sleepM = ScoreService::timeToMinutes($sleepStr);
        $managementM = ScoreService::timeToMinutes($managementStr);
        $utilizedM = ScoreService::timeToMinutes($utilizedStr);

        $wastedM = 24 * 60 - ($sleepM + $managementM + $utilizedM);
        $wastedM = max(0, $wastedM);
        $this->form['wasted'] = ScoreService::minutesToTime($wastedM);

        $maxManagementM = 24 * 60 - $sleepM - $utilizedM;
        if ($managementM > $maxManagementM) {
            $this->addError('form.management',
                'Management hours cannot exceed '.ScoreService::minutesToTime($maxManagementM).' based on sleep & utilized.'
            );
        }

        $maxUtilizedM = 24 * 60 - $sleepM - $managementM;
        if ($utilizedM > $maxUtilizedM) {
            $this->addError('form.utilized',
                'Utilized hours cannot exceed '.ScoreService::minutesToTime($maxUtilizedM).' based on sleep & management.'
            );
        }

        $totalM = $sleepM + $managementM + $utilizedM + $wastedM;
        if ($totalM !== 24 * 60) {
            $msg = 'The total of sleep, management, utilized, and wasted must equal 24 hours.';
            $this->addError('form.management', $msg);
            $this->addError('form.utilized', $msg);
        }
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

    private function calculateSleep(): void
    {
        if (! $this->form['yesterday_sleep_time'] || ! $this->form['today_wake_time']) {
            $this->form['sleep'] = null;

            return;
        }
        $yesterday = Carbon::parse($this->form['date'])->subDay();
        $today = Carbon::parse($this->form['date']);

        $sleepTime = Carbon::parse($yesterday->toDateString().' '.$this->form['yesterday_sleep_time']);
        $wakeTime = Carbon::parse($today->toDateString().' '.$this->form['today_wake_time']);

        if ($wakeTime->lessThan($sleepTime)) {
            $wakeTime->addDay();
        }

        $minutes = $sleepTime->diffInMinutes($wakeTime);
        if ($minutes > 21 * 60) {
            $minutes = 21 * 60;
        }
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        $this->form['sleep'] = sprintf('%02d:%02d', $hours, $mins);
    }

    public function updatedFormYesterdaySleepTime()
    {
        $this->calculateSleep();
    }

    public function updatedFormTodayWakeTime()
    {
        $this->calculateSleep();
    }

    public function updatedFormDate()
    {
        $this->get_previous_time_management_matrix();
    }

    public function save(): mixed
    {
        $this->syncChecklistArray();
        $this->recalculateHours();

        if ($this->getErrorBag()->isNotEmpty()) {
            return null;
        }

        $validated = $this->validate();
        $data = $validated['form'];
        $data['user_id'] = Auth::id();
        $diary = Diary::create($data);

        try {
            $score = ScoreService::calculateFromDiary($diary);
            DailyScore::updateOrCreate([
                'user_id' => $diary->user_id,
                'date' => $diary->date,
            ], ['score' => $score]);
        } catch (\Throwable $e) {
        }

        session()->flash('status', 'Diary created successfully!');

        return redirect()->route('score-calculator', ['date' => $diary->date]);
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

    public function getIsFormCompleteProperty()
    {
        return !empty($this->form['date'])
            && !empty($this->form['yesterday_sleep_time'])
            && !empty($this->form['today_wake_time'])
            && !empty($this->form['sleep'])
            && !empty($this->form['urgent_important'])
            && !empty($this->form['urgent_notimportant'])
            && !empty($this->form['noturgent_important'])
            && !empty($this->form['noturgent_notimportant'])
            && !empty($this->form['good_memo'])
            && !empty($this->form['text']);
    }

}
