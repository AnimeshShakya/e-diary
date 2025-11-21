<?php

namespace App\Livewire\Diaries;

use App\Models\Diary;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $searchDate = '';

    public bool $showViewModal = false;

    public ?int $viewId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSearchDate(): void
    {
        $this->resetPage();
    }

    public function view(Diary $diary): void
    {
        $this->viewId = $diary->id;
        $this->showViewModal = true;
    }

    protected function getDiaries(): LengthAwarePaginator
    {
        $user = Auth::user();

        return Diary::query()
            ->where('user_id', $user?->id)
            ->when($this->search !== '', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('text', 'like', '%'.$this->search.'%')
                        ->orWhere('good_memo', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->searchDate, function ($q) {
                $q->whereDate('date', $this->searchDate);
            })
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function render(): View
    {
        $viewDiary = $this->viewId ? Diary::find($this->viewId) : null;

        return view('livewire.diaries.index', [
            'diaries' => $this->getDiaries(),
            'viewDiary' => $viewDiary,
        ]);
    }

    public function layoutData(): array
    {
        return [
            'title' => 'My Diaries',
            'header' => 'My Diaries',
        ];
    }
}
