<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class YearlyChecklistComparison extends Component
{
    public array $chart = [];

    public function mount(): void
    {
        // defer heavy aggregation to frontend initialization for snappy loads
    }

    // called by the frontend via wire:init
    public function load(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        $chart = [];
        if (! Schema::hasTable('diary_checklist_items')) {
            $this->chart = ['labels' => [], 'datasets' => []];

            return;
        }

        $userId = Auth::id();
        $start = Carbon::now()->startOfYear()->toDateString();
        $end = Carbon::now()->endOfYear()->toDateString();
        // v2 cache key to avoid stale cached Collections
        $key = "dash:v2:ycc:{$userId}:".Carbon::now()->year;

        $chart = Cache::remember($key, now()->addMinutes(30), function () use ($userId, $start, $end) {
            $rows = DB::table('diary_checklist_items')
                ->select('checklist_id', DB::raw("DATE_FORMAT(date, '%Y-%m') as ym"), DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->groupBy('checklist_id', 'ym')
                ->get();

            $year = Carbon::now()->year;
            $months = collect(range(1, 12))->map(fn ($m) => Carbon::create($year, $m, 1)->format('Y-m'))->values();
            $daysInMonth = $months->mapWithKeys(function ($ym) {
                [$y, $m] = explode('-', $ym);

                return [$ym => Carbon::create((int) $y, (int) $m, 1)->daysInMonth];
            });
            $perChecklist = $rows->groupBy('checklist_id');
            $colors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'];
            $bgColors = ['rgba(16,185,129,0.35)', 'rgba(59,130,246,0.35)', 'rgba(245,158,11,0.35)', 'rgba(239,68,68,0.35)', 'rgba(139,92,246,0.35)'];

            // Resolve checklist names for nicer labels
            $checklistNames = collect();
            if ($perChecklist->isNotEmpty() && Schema::hasTable('checklists')) {
                $ids = $perChecklist->keys()->all();
                $checklistNames = DB::table('checklists')->whereIn('id', $ids)->pluck('name', 'id');
            }
            $datasets = [];
            $i = 0;
            foreach ($perChecklist as $checklistId => $group) {
                $map = $group->keyBy('ym');
                $datasets[] = [
                    'label' => (string) ($checklistNames[$checklistId] ?? "Item #{$checklistId}"),
                    // Convert counts to completion rate (%) = (days completed / total days in month) * 100
                    'data' => $months->map(function ($ym) use ($map, $daysInMonth) {
                        $count = (int) ($map[$ym]->cnt ?? 0);
                        $total = max(1, (int) $daysInMonth[$ym]);

                        return round(($count / $total) * 100, 1);
                    })->values()->all(),
                    'borderColor' => $colors[$i % count($colors)],
                    'backgroundColor' => $bgColors[$i % count($bgColors)],
                    'borderWidth' => 1,
                ];
                $i++;
            }

            return [
                'labels' => $months->all(),
                'datasets' => $datasets,
            ];
        });
        // Ensure deeply converted to plain arrays (avoid Collections inside cache)
        $this->chart = collect($chart)->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.yearly-checklist-comparison');
    }
}
