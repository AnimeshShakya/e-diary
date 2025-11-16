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

    /** @var array{morning_affirmation:null|string, night_affirmation:null|string} */
    public array $form = [
        'morning_affirmation' => null,
        'night_affirmation' => null,
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
            'morning_affirmation' => $affirmation->morning_affirmation,
            'night_affirmation' => $affirmation->night_affirmation,
        ];
    }

    protected function rules(): array
    {
        return [
            'form.morning_affirmation' => ['required', 'string', 'min:3'],
            'form.night_affirmation' => ['required', 'string', 'min:3'],
        ];
    }

    public function save(): mixed
    {
        $validated = $this->validate();
        $data = $validated['form'];

        $this->record->update($data);

        session()->flash('status', 'Affirmations updated successfully');

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
