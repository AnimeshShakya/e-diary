<?php

namespace App\Providers;

use App\Models\Checklist;
use App\Observers\ChecklistObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Checklist::observe(ChecklistObserver::class);
    }
}
