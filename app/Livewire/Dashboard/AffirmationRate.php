<?php

namespace App\Livewire\Dashboard;

use App\Models\AffirmationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
// Deprecated: Removed from dashboard per product decision
class AffirmationRate extends Component
{
    public array $chart = [];

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        if (! Schema::hasTable('affirmation_logs')) {
            $this->chart = ['labels' => ['Morning', 'Night'], 'datasets' => [['label' => 'Completion % (30 days)', 'data' => [0, 0], 'backgroundColor' => ['#34D399', '#EF4444']]]];

            return;
        }

        $userId = Auth::id();
        $start = Carbon::today()->subDays(29)->toDateString();
        $key = "dash:ar:{$userId}:{$start}";

        $this->chart = Cache::remember($key, now()->addMinutes(15), function () use ($userId, $start) {
            $row = AffirmationLog::query()
                ->select(DB::raw('SUM(morning_done) as m'), DB::raw('SUM(night_done) as n'))
                ->where('user_id', $userId)
                ->where('date', '>=', $start)
                ->first();

            $totalDays = 30; // window size
            $morning = (int) ($row->m ?? 0);
            $night = (int) ($row->n ?? 0);
            $chart = [
                'labels' => ['Morning', 'Night'],
                'datasets' => [[
                    'label' => 'Completion % (30 days)',
                    'data' => [round($morning / $totalDays * 100), round($night / $totalDays * 100)],
                    'backgroundColor' => ['#34D399', '#EF4444'],
                ]],
            ];

            return $chart;
        });
    }

    public function render()
    {
        abort(404);
    }
}
