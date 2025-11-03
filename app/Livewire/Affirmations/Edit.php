<?php

namespace App\Livewire\Affirmations;

use App\Models\Affirmation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public Affirmation $record;

    /** @var array{type:null|int|string, description:null|string} */
    public array $form = [
        'type' => null,
        'description' => null,
    ];

    public function mount(Affirmation $affirmation): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }
        if ($affirmation->user_id !== $user->id && ! ($user->isAdmin())) {
            abort(403);
        }
        $this->record = $affirmation;

        $this->form = [
            'type' => $affirmation->type ? 1 : 0,
            'description' => $affirmation->description,
        ];
    }

    protected function rules(): array
    {
        return [
            'form.type' => ['required', 'in:0,1'],
            'form.description' => ['required', 'string', 'min:3'],
        ];
    }

    public function save(): mixed
    {
        $validated = $this->validate();
        $data = $validated['form'];
        $data['type'] = (bool) ((int) $data['type']);

        $this->record->update($data);

        session()->flash('status', 'Affirmation updated');

        return redirect()->route('affirmations.index');
    }

    public function delete(): mixed
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }
        if ($this->record->user_id !== $user->id && ! ($user->isAdmin())) {
            abort(403);
        }

        $this->record->delete();

        session()->flash('status', 'Affirmation deleted');

        return redirect()->route('affirmations.index');
    }

    public function render(): View
    {
        return view('livewire.affirmations.edit');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Edit Affirmation',
            'header' => 'Edit Affirmation',
        ];
    }
}
