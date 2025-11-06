<?php

namespace App\Livewire;

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

        if ($mode === 'monthly') {
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
        $startDate = Carbon::now()->subDays(30);
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
            $this->totalChecklist = count($checklistArray);
            $this->completedChecklist = collect($checklistArray)->where('completed', true)->count();

            // Auto-calculate score when data is loaded
            $this->calculateScore();
        } else {
            $this->hasData = false;
            $this->resetData();
        }
    }

    private function calculateDiaryScore($diary)
    {
        // Load checklist data
        $checklistArray = json_decode($diary->checklist_array, true) ?? [];
        $totalChecklist = count($checklistArray);
        $completedChecklist = collect($checklistArray)->where('completed', true)->count();

        // Calculate checklist score
        $checklistScore = $totalChecklist > 0
            ? ($completedChecklist / $totalChecklist) * 5
            : 0;

        // Sleep score
        $sleep = $diary->sleep ?? 7;
        if ($sleep >= 6 && $sleep <= 8) {
            $sleepScore = 10;
        } elseif ($sleep == 5 || $sleep == 9) {
            $sleepScore = 7;
        } elseif ($sleep == 4 || $sleep == 10) {
            $sleepScore = 4;
        } else {
            $sleepScore = 1;
        }
        $sleepFinal = ($sleepScore / 10) * 2.5;

        // Management score
        $management = $diary->management ?? 1;
        $managementScore = max(0, 10 - ($management - 1));
        $managementFinal = ($managementScore / 10) * 1.5;

        // Utilized score
        $utilized = $diary->utilized ?? 16;
        $utilizedScore = min(($utilized / 16) * 10, 10);
        $utilizedFinal = ($utilizedScore / 10) * 2;

        // Wasted score
        $wasted = $diary->wasted ?? 0;
        $wastedScore = max(0, 10 - $wasted);
        $wastedFinal = ($wastedScore / 10) * 1;

        // Total score
        $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;

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

    public function calculateScore()
    {
        // -------- CHECKLIST (50%) --------
        $checklistScore = $this->totalChecklist > 0
            ? ($this->completedChecklist / $this->totalChecklist) * 5
            : 0;

        // -------- SLEEP SCORE --------
        if ($this->sleep >= 6 && $this->sleep <= 8) {
            $sleepScore = 10;
        } elseif ($this->sleep == 5 || $this->sleep == 9) {
            $sleepScore = 7;
        } elseif ($this->sleep == 4 || $this->sleep == 10) {
            $sleepScore = 4;
        } else {
            $sleepScore = 1;
        }
        $sleepFinal = ($sleepScore / 10) * 2.5;

        // -------- MANAGEMENT SCORE (low is better) --------
        $managementScore = max(0, 10 - ($this->management - 1));
        $managementFinal = ($managementScore / 10) * 1.5;

        // -------- UTILIZED SCORE (high is better) --------
        $utilizedScore = min(($this->utilized / 16) * 10, 10);
        $utilizedFinal = ($utilizedScore / 10) * 2;

        // -------- WASTED SCORE (low is better) --------
        $wastedScore = max(0, 10 - $this->wasted);
        $wastedFinal = ($wastedScore / 10) * 1;

        // -------- TOTAL TIME SCORE (50%) --------
        $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;

        // -------- FINAL SCORE out of 10 --------
        $this->score = round($checklistScore + $timeScore, 2);
    }

    public function render()
    {
        if ($this->mode === 'monthly') {
            return view('livewire.score-calculator-monthly');
        }

        return view('livewire.score-calculator');
    }
}
