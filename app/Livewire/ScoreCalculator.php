<?php

namespace App\Livewire;

use App\Models\Checklist;
use App\Models\Diary;
use App\Services\ScoreService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ScoreCalculator extends Component
{
    public $selectedDate;
    public $totalChecklist = 0;
    public $completedChecklist = 0;
    public $sleep = '07:00';
    public $management = '01:00';
    public $utilized = '16:00';
    public $wasted = '00:00';
    public $score = 0;
    public $hasData = false;
    public $mode = 'single';
    public $monthlyScores = [];
    public $averageScore = 0;
    protected $queryString = ['selectedDate' => ['as' => 'date']];
    public $detail_data = [];

    public function mount($mode = 'single', $date = null)
    {
        $this->mode = $mode;
        $this->selectedDate = $date ?? Carbon::today()->format('Y-m-d');
    }

    public function load(): void
    {
        if ($this->mode === 'monthly') {
            $this->loadMonthlyData();
        } else {
            $this->loadDiaryData();
        }
    }

    public function updatedSelectedDate()
    {
        $this->loadDiaryData();
    }

    public function loadMonthlyData()
    {
        $startDate = Carbon::now()->subDays(7)->toDateString();
        $endDate = Carbon::now()->toDateString();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('daily_scores')) {
                $rows = \App\Models\DailyScore::query()
                    ->where('user_id', Auth::id())
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'desc')
                    ->get();

                $this->monthlyScores = [];
                $totalScore = 0;

                foreach ($rows as $r) {
                    $this->monthlyScores[] = [
                        'date' => Carbon::parse($r->date)->format('M d'),
                        'fullDate' => Carbon::parse($r->date)->format('F d, Y'),
                        'score' => $r->score,
                    ];
                    $totalScore += $r->score;
                }

                $this->averageScore = count($this->monthlyScores) > 0
                    ? round($totalScore / count($this->monthlyScores), 2)
                    : 0;

                $this->hasData = count($this->monthlyScores) > 0;

                return;
            }
        } catch (\Throwable $e) {
        }

        $diaries = Diary::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $this->monthlyScores = [];
        $totalScore = 0;

        foreach ($diaries as $diary) {
            $score = $this->calculateDiaryScore($diary);
            $this->monthlyScores[] = [
                'date' => Carbon::parse($diary->date)->format('M d'),
                'fullDate' => Carbon::parse($diary->date)->format('F d, Y'),
                'score' => $score,
            ];
            $totalScore += $score;
        }

        $this->averageScore = count($this->monthlyScores) > 0
            ? round($totalScore / count($this->monthlyScores), 2)
            : 0;

        $this->hasData = count($this->monthlyScores) > 0;
    }

    public function loadDiaryData()
    {
        $diary = Diary::where('user_id', Auth::id())
            ->where('date', $this->selectedDate)
            ->first();

        if ($diary) {
            $this->hasData = true;

            $this->sleep = $diary->sleep ?? '07:00';
            $this->management = $diary->management ?? '01:00';
            $this->utilized = $diary->utilized ?? '16:00';
            $this->wasted = $diary->wasted ?? '00:00';

            $checklistArray = json_decode($diary->checklist_array, true) ?? [];
            $this->totalChecklist = Checklist::query()->count();
            $this->completedChecklist = count($checklistArray);

            $score_service = ScoreService::calculateFromDiary($diary);
            $this->score = $score_service['round'];
            $this->detail_data = $score_service['detail'];
        } else {
            $this->hasData = false;
            $this->resetData();
        }
    }

    private function calculateDiaryScore($diary)
    {
        return ScoreService::calculateFromDiary($diary);
    }

    public function resetData()
    {
        $this->totalChecklist = 0;
        $this->completedChecklist = 0;
        $this->sleep = 7;
        $this->management = 1;
        $this->utilized = 16;
        $this->wasted = 0;
        $this->score = 0;
    }

    public function render()
    {
        if ($this->mode === 'monthly') {
            return view('livewire.score-calculator-monthly');
        }

        return view('livewire.score-calculator');
    }
}
