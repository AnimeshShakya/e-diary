<?php

namespace App\Observers;

use App\Models\Checklist;
use Illuminate\Support\Facades\Cache;

class ChecklistObserver
{
    /**
     * Handle the Checklist "created" event.
     */
    public function created(Checklist $checklist): void
    {
        $this->clearChecklistCache();
    }

    /**
     * Handle the Checklist "deleted" event.
     */
    public function deleted(Checklist $checklist): void
    {
        $this->clearChecklistCache();
    }

    /**
     * Handle the Checklist "restored" event.
     */
    public function restored(Checklist $checklist): void
    {
        $this->clearChecklistCache();
    }

    /**
     * Handle the Checklist "force deleted" event.
     */
    public function forceDeleted(Checklist $checklist): void
    {
        $this->clearChecklistCache();
    }

    /**
     * Clear the checklist count cache
     */
    private function clearChecklistCache(): void
    {
        Cache::forget('total_checklist_count');
    }
}
