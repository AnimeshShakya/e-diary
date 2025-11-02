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

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.diaries.index', [
            'diaries' => $this->getDiaries(),
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
