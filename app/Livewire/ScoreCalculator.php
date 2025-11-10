<?php

namespace App\Livewire;

use App\Models\Checklist;
use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\ScoreService;

#[Layout('layouts.admin')]
class ScoreCalculator extends Component
{
    public $selectedDate;

    public $totalChecklist = 0;

    public $completedChecklist = 0;

    public $sleep = 7;

    public $management = 1;

    public $utilized = 16;

    public $wasted = 0;

    public $score = 0;

    public $hasData = false;

    public $mode = 'single'; // 'single' or 'monthly'

    public $monthlyScores = [];

    public $averageScore = 0;

    protected $queryString = ['selectedDate' => ['as' => 'date']];

    public function mount($mode = 'single', $date = null)
    {
        $this->mode = $mode;
        $this->selectedDate = $date ?? Carbon::today()->format('Y-m-d');
        // Defer loading heavy diary queries until the frontend triggers it
        // via wire:init. This keeps initial navigation fast.
    }

    // Called from Blade via wire:init to populate state after initial render
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
        // Prefer precomputed DailyScore records if available for faster loads
        $startDate = Carbon::now()->subDays(7)->toDateString();
        $endDate = Carbon::now()->toDateString();

        // If the daily_scores table exists, query it. Otherwise fallback to diaries.
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
            // If anything goes wrong querying the table, fall back to diary-based compute
        }

        // Fallback: compute from diaries directly
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

            // Load time management data from diary
            $this->sleep = $diary->sleep ?? 7;
            $this->management = $diary->management ?? 1;
            $this->utilized = $diary->utilized ?? 16;
            $this->wasted = $diary->wasted ?? 0;

            // Load checklist data
            $checklistArray = json_decode($diary->checklist_array, true) ?? [];
            $this->totalChecklist = Checklist::query()->count();
            $this->completedChecklist = count($checklistArray);

            // Auto-calculate score when data is loaded
            $this->score = ScoreService::calculateFromDiary($diary);
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
