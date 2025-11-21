<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily diary reminders to be sent every day at 8:00 PM
Schedule::command('diary:send-reminders')
    ->dailyAt('22:00')
    ->timezone('Asia/Kathmandu')
    ->withoutOverlapping()
    ->runInBackground();

// Schedule backfill of daily_scores to run nightly during off-peak hours
Schedule::command('backfill:daily-scores')
    ->dailyAt('1:10')
    ->timezone('Asia/Kathmandu')
    ->withoutOverlapping()
    ->runInBackground();
