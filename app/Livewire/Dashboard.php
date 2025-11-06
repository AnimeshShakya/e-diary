<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function mount(): void
    {
        // Redirect admin users to admin dashboard
        $user = Auth::user();
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard', [
            'title' => 'Dashboard',
            'header' => 'My Dashboard',
        ]);
    }
}
