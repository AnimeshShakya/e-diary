<?php

namespace App\Livewire\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Password extends Component
{
    protected function rules(): array
    {
        return [
            'form.current_password' => ['required', 'string', 'min:8'],
            'form.new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function message(): array
    {
        return [
            'form.current_password.required' => 'Current password is required.',
            'form.current_password.string' => 'Current password must be a string.',
            'form.current_password.min' => 'Current password must be at least :min characters.',
            'form.new_password.required' => 'New password is required.',
            'form.new_password.string' => 'New password must be a string.',
            'form.new_password.min' => 'New password must be at least :min characters.',
            'form.new_password.confirmed' => 'New password confirmation does not match.',
        ];
    }
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
