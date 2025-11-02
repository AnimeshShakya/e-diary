<?php

namespace App\Livewire\Admin\Checklists;

use App\Models\Checklist;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Create extends Component
{
    /** @var array{name:string,description:?string} */
    public array $form = [
        'name' => '',
        'description' => null,
    ];

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', 'unique:checklists,name'],
            'form.description' => ['nullable', 'string'],
        ];
    }

    public function save(): mixed
    {
        $data = $this->validate()['form'];
        Checklist::create($data);
        session()->flash('status', 'Checklist created');

        return redirect()->route('admin.checklists.index');
    }

    public function render(): View
    {
        return view('livewire.admin.checklists.create');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'New Checklist',
            'header' => 'Create Checklist',
        ];
    }
}
