<?php

namespace App\Livewire\Admin;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public int $usersCount = 0;

    public int $diariesCount = 0;

    public int $flaggedCount = 0;

    public array $recentActivity = [];

    public function mount(): void
    {
        $this->usersCount = User::count();
        $this->diariesCount = Diary::count();
        // $this->recentActivity = DB::table('admin_audit_logs')->latest()->limit(10)->get();
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard', [
            'title' => 'Admin Dashboard',
            'header' => 'Overview',
        ]);
    }
}
