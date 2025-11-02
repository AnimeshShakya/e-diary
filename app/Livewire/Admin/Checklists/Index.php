<?php

namespace App\Livewire\Admin\Checklists;

use App\Models\Checklist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
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

    public function delete(int $id): void
    {
        Checklist::query()->whereKey($id)->delete();
        session()->flash('status', 'Checklist deleted');
        $this->resetPage();
    }

    protected function getChecklists(): LengthAwarePaginator
    {
        return Checklist::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.admin.checklists.index', [
            'checklists' => $this->getChecklists(),
        ]);
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Checklists',
            'header' => 'Checklists',
        ];
    }
}
