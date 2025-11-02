<?php

namespace App\Livewire\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Password extends Component
{
    public function render(): View
    {
        return view('livewire.settings.password');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Password Settings',
            'header' => 'Password Settings',
        ];
    }
}
