<?php

namespace App\Livewire\Affirmations;

use App\Models\Affirmation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Index extends Component
{
    public ?Affirmation $affirmation = null;

    public function mount(): void
    {
        $this->affirmation = Affirmation::query()
            ->where('user_id', Auth::id())
            ->first();
    }

    public function delete(int $affirmationId): mixed
    {
        $affirmation = Affirmation::findOrFail($affirmationId);
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        if ($affirmation->user_id !== $user->id && ! ($user->isAdmin())) {
            abort(403);
        }

        $affirmation->delete();
        $this->affirmation = null;
        session()->flash('status', 'Affirmation deleted');

        return redirect()->route('affirmations.index');
    }

    public function render(): View
    {
        return view('livewire.affirmations.index');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Affirmations',
            'header' => 'Affirmations',
        ];
    }
}
