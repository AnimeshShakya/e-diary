<?php

namespace App\Livewire\Admin\Checklists;

use App\Models\Checklist;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public Checklist $checklist;

    /** @var array{name:string,description:?string} */
    public array $form = [];

    public function mount(Checklist $checklist): void
    {
        $this->checklist = $checklist;
        $this->form = [
            'name' => $checklist->name,
            'description' => $checklist->description,
        ];
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', 'unique:checklists,name,'.$this->checklist->id],
            'form.description' => ['nullable', 'string'],
        ];
    }

    public function save(): mixed
    {
        $data = $this->validate()['form'];
        $this->checklist->update($data);
        session()->flash('status', 'Checklist updated');

        return redirect()->route('admin.checklists.index');
    }

    public function render(): View
    {
        return view('livewire.admin.checklists.edit');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Edit Checklist',
            'header' => 'Edit Checklist',
        ];
    }
}
