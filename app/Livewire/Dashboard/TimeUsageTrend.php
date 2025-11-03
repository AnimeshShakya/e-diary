<?php

namespace App\Livewire\Dashboard;

use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class TimeUsageTrend extends Component
{
    public array $chart = [];

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        $userId = Auth::id();
        $start = Carbon::today()->subDays(59)->toDateString();
        $key = "dash:tut:{$userId}:{$start}";

        $this->chart = Cache::remember($key, now()->addMinutes(15), function () use ($userId, $start) {
            $rows = Diary::query()
                ->select(
                    'date',
                    DB::raw('AVG(sleep) as avg_sleep'),
                    DB::raw('AVG(management) as avg_mgmt'),
                    DB::raw('AVG(utilized) as avg_util'),
                    DB::raw('AVG(wasted) as avg_waste')
                )
                ->where('user_id', $userId)
                ->where('date', '>=', $start)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $rows->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))->values()->all();
            $avgSleep = $rows->pluck('avg_sleep')->map(fn ($v) => (int) round($v))->values()->all();
            $avgMgmt = $rows->pluck('avg_mgmt')->map(fn ($v) => (int) round($v))->values()->all();

            // 7-day simple moving average for sleep
            $ma7 = [];
            for ($i = 0; $i < count($avgSleep); $i++) {
                if ($i < 6) {
                    $ma7[] = null;

                    continue;
                }
                $slice = array_slice($avgSleep, $i - 6, 7);
                $ma7[] = (int) round(array_sum($slice) / 7);
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Sleep (avg)',
                        'data' => $avgSleep,
                        'borderColor' => '#A78BFA',
                        'fill' => false,
                    ],
                    [
                        'label' => 'Sleep (MA7)',
                        'data' => $ma7,
                        'borderColor' => '#F59E0B',
                        'borderDash' => [6, 4],
                        'fill' => false,
                    ],
                    [
                        'label' => 'Management (avg)',
                        'data' => $avgMgmt,
                        'borderColor' => '#34D399',
                        'fill' => false,
                    ],
                ],
            ];
        });
    }

    public function render()
    {
        return view('livewire.dashboard.time-usage-trend');
    }
}
