<?php

namespace App\Livewire;

use App\Models\Checklist;
use App\Models\Diary;
use App\Models\DailyScore;
use App\Services\ScoreService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
    public $stats = [];

    public $dailyScores = [];

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
        $endDate   = Carbon::now()->toDateString();

        $this->monthlyScores = [];
        $totalScore = 0;

        // Optimized: Single query with only needed columns
        $rows = DailyScore::query()
            ->select(['date', 'score'])
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // If no daily scores, fallback to diaries
        if ($rows->isEmpty()) {
            $rows = Diary::query()
                ->select(['date', 'sleep', 'management', 'utilized', 'wasted', 'checklist_array'])
                ->where('user_id', Auth::id())
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get()
                ->map(function($diary) {
                    $scoreData = ScoreService::calculateFromDiary($diary);
                    return (object)[
                        'date' => $diary->date,
                        'score' => $scoreData['round']
                    ];
                });
        }

        // Single loop to build everything
        foreach ($rows as $r) {
            $score = $r->score;
            $dateObj = Carbon::parse($r->date);

            $this->monthlyScores[] = [
                'date'     => $dateObj->format('M d'),
                'fullDate' => $dateObj->format('F d, Y'),
                'score'    => $score,
                'class'    => $this->getScoreClass($score), // Helper method
            ];

            $totalScore += $score;
        }

        $count = count($this->monthlyScores);
        $this->averageScore = $count > 0 ? round($totalScore / $count, 2) : 0;
        $this->hasData = $count > 0;

        // Calculate stats efficiently
        if ($count > 0) {
            $scores = array_column($this->monthlyScores, 'score');
            $goodDays = count(array_filter($scores, fn($s) => $s >= 7));
            $maxScore = max($scores);

            $this->stats = [
                'goodDays' => $goodDays,
                'total' => $count,
                'max' => $maxScore,
                'badgeClass' => $this->getBadgeClass($this->averageScore),
                'badgeText' => $this->getBadgeText($this->averageScore),
            ];

            // Use monthlyScores directly instead of duplicating
            $this->dailyScores = $this->monthlyScores;
        } else {
            $this->stats = [
                'goodDays' => 0,
                'total' => 0,
                'max' => 0,
                'badgeClass' => '',
                'badgeText' => '',
            ];
            $this->dailyScores = [];
        }
    }

    public function loadDiaryData()
    {
        // Optimized: Only select needed columns
        $diary = Diary::query()
            ->select(['date', 'sleep', 'management', 'utilized', 'wasted', 'checklist_array'])
            ->where('user_id', Auth::id())
            ->where('date', $this->selectedDate)
            ->first();

        if (!$diary) {
            $this->hasData = false;
            $this->resetData();
            return;
        }

        $this->hasData = true;

        $this->sleep      = $diary->sleep      ?? '07:00';
        $this->management = $diary->management ?? '01:00';
        $this->utilized   = $diary->utilized   ?? '16:00';
        $this->wasted     = $diary->wasted     ?? '00:00';

        $checklistArray = json_decode($diary->checklist_array, true) ?? [];

        // Cached checklist count (updated every hour or on checklist changes)
        $this->totalChecklist = Cache::remember(
            'total_checklist_count',
            now()->addHour(),
            fn() => Checklist::count()
        );
        
        $this->completedChecklist = count($checklistArray);

        $scoreData = ScoreService::calculateFromDiary($diary);
        $final_score = $scoreData['round'];
        try {
            DailyScore::updateOrCreate([
                'user_id' => Auth::id(),
                'date' => $diary->date,
            ], [
                'score' => $final_score,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error updating DailyScore for user '.Auth::id().' on date'.$diary->date.': '.$e->getMessage());
        }

        $this->score       = $scoreData['round'];
        $this->detail_data = $scoreData['detail'];
    }

    public function switchToDate($date)
    {
        $this->selectedDate = $date;
        $this->mode = 'single';
        $this->loadDiaryData();
    }

    public function resetData()
    {
        $this->totalChecklist     = 0;
        $this->completedChecklist = 0;

        $this->sleep      = '07:00';
        $this->management = '01:00';
        $this->utilized   = '16:00';
        $this->wasted     = '00:00';

        $this->score       = 0;
        $this->detail_data = [];
    }

    // Helper method for score class
    private function getScoreClass($score): string
    {
        return match(true) {
            $score >= 8 => 'bg-green-500/20 text-green-400 border border-green-500/30',
            $score >= 6 => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
            $score >= 4 => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
            default => 'bg-red-500/20 text-red-400 border border-red-500/30',
        };
    }

    // Helper method for badge class
    private function getBadgeClass($average): string
    {
        return match(true) {
            $average >= 8 => 'bg-green-500/20 text-green-400 border border-green-500/30',
            $average >= 6 => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
            $average >= 4 => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
            default => 'bg-red-500/20 text-red-400 border border-red-500/30',
        };
    }

    // Helper method for badge text
    private function getBadgeText($average): string
    {
        return match(true) {
            $average >= 8 => '🌟 Excellent Performance',
            $average >= 6 => '👍 Good Progress',
            $average >= 4 => '⚡ Keep Going',
            default => '💪 Room for Improvement',
        };
    }

    public function render()
    {
        return $this->mode === 'monthly'
            ? view('livewire.score-calculator-monthly')
            : view('livewire.score-calculator');
    }
}