<?php

namespace App\Livewire\Dashboard;

use App\Models\DiaryChecklistItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MonthlyChecklistBar extends Component
{
    public array $chart = [];

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        if (! Schema::hasTable('diary_checklist_items')) {
            $this->chart = ['labels' => [], 'datasets' => [['label' => 'Completed (Last 30 days)', 'data' => [], 'backgroundColor' => '#6366F1']]];

            return;
        }
        $userId = Auth::id();
        $start = Carbon::today()->subDays(29)->toDateString();
        $end = Carbon::today()->toDateString();
        // v2 cache key to avoid stale cached Collections
        $key = "dash:v2:mcb:{$userId}:{$start}:{$end}";

        $chart = Cache::remember($key, now()->addMinutes(15), function () use ($userId, $start, $end) {
            $rows = DiaryChecklistItem::query()
                ->select('checklist_id', DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->groupBy('checklist_id')
                ->with('checklist:id,name')
                ->get();

            $labels = $rows->map(fn ($r) => $r->checklist->name ?? ('Item #'.$r->checklist_id))->values()->all();
            $data = $rows->pluck('cnt')->values()->all();

            return [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Completed (Last 30 days)',
                    'data' => $data,
                    'backgroundColor' => '#6366F1',
                ]],
            ];
        });

        // Ensure deeply converted to plain arrays (avoid Collections inside cache)
        $this->chart = collect($chart)->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.monthly-checklist-bar');
    }
}
