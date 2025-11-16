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
    /** @var array{morning_affirmation:null|string, night_affirmation:null|string} */
    public array $form = [
        'morning_affirmation' => null,
        'night_affirmation' => null,
    ];

    public function mount(): mixed
    {
        // Redirect to edit if user already has an affirmation
        $existing = Affirmation::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('affirmations.edit', $existing);
        }

        return null;
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
        $data['user_id'] = Auth::id();

        $affirmation = Affirmation::create($data);

        session()->flash('status', 'Affirmations created successfully');

        return redirect()->route('affirmations.index');
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
