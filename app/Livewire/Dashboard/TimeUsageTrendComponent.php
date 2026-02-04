<?php

namespace App\Livewire\Dashboard;

use App\Models\TimeUsageTrend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class TimeUsageTrendComponent extends Component
{
    public array $chart = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function load(): void
    {
        $this->loadData();
        $this->dispatch('chart-data-loaded-time-usage');
    }

    protected function loadData(): void
    {
        $userId = Auth::id();
        $startDate = Carbon::today()->subDays(59)->toDateString();
        $cacheKey = "dashboard:trend:{$userId}:{$startDate}";

        $this->chart = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId, $startDate) {

            $rows = TimeUsageTrend::query()
                ->where('user_id', $userId)
                ->where('date', '>=', $startDate)
                ->orderBy('date')
                ->get();

            if ($rows->isEmpty()) {
                return [
                    'labels' => [],
                    'datasets' => [],
                ];
            }

            $labels = [];
            $sleep = [];
            $management = [];
            $utilized = [];
            $wasted = [];

            foreach ($rows as $row) {
                $labels[] = Carbon::parse($row->date)->format('M d');
                $sleep[] = round($row->sleep_min / 60, 2);
                $management[] = round($row->management_min / 60, 2);
                $utilized[] = round($row->utilized_min / 60, 2);
                $wasted[] = round($row->wasted_min / 60, 2);
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Sleep',
                        'data' => $sleep,
                        'borderColor' => '#A78BFA',
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                    [
                        'label' => 'Management',
                        'data' => $management,
                        'borderColor' => '#34D399',
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                    [
                        'label' => 'Utilized',
                        'data' => $utilized,
                        'borderColor' => '#3B82F6',
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                    [
                        'label' => 'Wasted',
                        'data' => $wasted,
                        'borderColor' => '#EF4444',
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                ],
            ];
        });
    }

    public function render()
    {
        return view('livewire.dashboard.time-usage-trend-component');
    }
}
