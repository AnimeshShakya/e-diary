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

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            Checklist::query()->whereKey($this->deleteId)->delete();
            session()->flash('status', 'Checklist deleted');
            $this->deleteId = null;
            $this->showDeleteModal = false;
            $this->resetPage();
        }
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
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
