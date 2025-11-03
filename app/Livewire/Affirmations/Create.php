<?php

namespace App\Livewire\Affirmations;

use App\Models\Affirmation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Create extends Component
{
    /** @var array{type:null|int|string, description:null|string} */
    public array $form = [
        'type' => null,
        'description' => null,
    ];

    public function mount(): mixed
    {
        // Redirect to edit if user already has an affirmation
        $existing = Affirmation::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('affirmations.edit', $existing);
        }

        // Default type to morning (1)
        $this->form['type'] = 1;

        return null;
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
        $data['user_id'] = Auth::id();
        // Cast to boolean
        $data['type'] = (bool) ((int) $data['type']);

        $affirmation = Affirmation::create($data);

        session()->flash('status', 'Affirmation created');

        return redirect()->route('affirmations.edit', $affirmation);
    }

    public function render(): View
    {
        return view('livewire.affirmations.create');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Create Affirmation',
            'header' => 'Create Affirmation',
        ];
    }
}
