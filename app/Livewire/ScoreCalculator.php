<?php

namespace App\Livewire;

use App\Models\Checklist;
use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

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
            $this->totalChecklist = Checklist::count();
            $this->completedChecklist = count($checklistArray);

            // Auto-calculate score when data is loaded
            $daily_score = $this->calculateScore();
            $this->score = $daily_score;
        } else {
            $this->hasData = false;
            $this->resetData();
        }
    }

    private function calculateDiaryScore($diary)
    {
        // Load checklist data
        $checklistArray = json_decode($diary->checklist_array, true) ?? [];
        $completedChecklist = count($checklistArray);
        $totalChecklist = Checklist::count();

        // Calculate checklist score
        $checklistScore = $totalChecklist > 0
            ? ($completedChecklist / $totalChecklist) * 5
            : 0;

        // Sleep score
        $sleep = $diary->sleep ?? 7;
        if ($sleep == 7) {
            $sleepScore = 10;
        } elseif ($sleep == 6 && $sleep == 8) {
            $sleepScore = 9;
        } elseif ($sleep == 5 || $sleep == 9) {
            $sleepScore = 7;
        } elseif ($sleep == 4 || $sleep == 10) {
            $sleepScore = 4;
        } else {
            $sleepScore = 1;
        }
        $sleepFinal = ($sleepScore / 10) * 2;

        // Management score
        $management = $diary->management ?? 2;
        $managementScore = max(0, 10 - ($management - 1));
        $managementFinal = ($managementScore / 10) * 1;

        // Utilized score
        $utilized = $diary->utilized ?? 16;
        $utilizedScore = min(($utilized / 16) * 10, 10);
        $utilizedFinal = ($utilizedScore / 10) * 3;

        // Wasted score
        $wasted = $diary->wasted ?? 0;
        $wastedScore = max(0, 10 - $wasted);
        $wastedFinal = ($wastedScore / 10) * 3;

        // Total score
        $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;
        $timeScore = ($timeScore / 9) * 5;

        return round($checklistScore + $timeScore, 2);
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
