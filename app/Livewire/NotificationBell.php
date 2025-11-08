<?php

namespace App\Livewire;

use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $showNotifications = false;

    public array $notifications = [];

    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->checkMonthlyCompletion();
    }

    public function toggleNotifications(): void
    {
        $this->showNotifications = ! $this->showNotifications;
    }

    public function markAsRead(int $index): void
    {
        if (isset($this->notifications[$index])) {
            $this->notifications[$index]['read'] = true;
            $this->unreadCount = max(0, $this->unreadCount - 1);

            // Store in session
            session()->put('notifications', $this->notifications);
        }
    }

    public function clearAll(): void
    {
        $this->notifications = [];
        $this->unreadCount = 0;
        session()->forget('notifications');
    }

    protected function checkMonthlyCompletion(): void
    {
        // Load notifications from session
        $sessionNotifications = session()->get('notifications', []);

        // Check if we already have a notification for this month
        $currentMonth = Carbon::now()->format('Y-m');
        $hasMonthlyNotification = collect($sessionNotifications)->contains(function ($notif) use ($currentMonth) {
            return isset($notif['type']) && $notif['type'] === 'monthly_completion' && $notif['month'] === $currentMonth;
        });

        if (! $hasMonthlyNotification) {
            // Check if user completed all checklists for the month
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $diariesCount = Diary::where('user_id', Auth::id())
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->count();

            $daysInMonth = Carbon::now()->daysInMonth;
            $currentDay = Carbon::now()->day;

            // If user has created diary for every day so far this month
            if ($diariesCount >= $currentDay && $currentDay >= 28) {
                $sessionNotifications[] = [
                    'type' => 'monthly_completion',
                    'month' => $currentMonth,
                    'title' => 'Monthly Checklist Completed! 🎉',
                    'message' => 'Congratulations! You\'ve completed your diary entries for this month.',
                    'timestamp' => Carbon::now()->toIso8601String(),
                    'read' => false,
                ];

                session()->put('notifications', $sessionNotifications);
            }
        }

        $this->notifications = session()->get('notifications', []);
        $this->unreadCount = collect($this->notifications)->where('read', false)->count();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
