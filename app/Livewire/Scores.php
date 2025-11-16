<?php

namespace App\Livewire;

use App\Models\DailyScore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Scores extends Component
{
    use WithPagination;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public $perPage = 15;

    protected $queryString = ['start_date', 'end_date'];

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function exportCsv()
    {
        $query = $this->buildQuery();

        $filename = 'daily-scores-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['date', 'score']);

            $query->orderBy('date', 'desc')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [Carbon::parse($row->date)->format('Y-m-d'), (string) $row->score]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function buildQuery()
    {
        $q = DailyScore::query()->where('user_id', Auth::id());

        if ($this->start_date) {
            $q->where('date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $q->where('date', '<=', $this->end_date);
        }

        return $q;
    }

    public function render()
    {
        $scores = $this->buildQuery()->orderBy('date', 'desc')->paginate($this->perPage);

        return view('livewire.scores', [
            'scores' => $scores,
        ]);
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Scores',
            'header' => 'Daily Scores',
        ];
    }
}
