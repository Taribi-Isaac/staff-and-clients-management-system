<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule recurring invoice checks - run daily at 9:00 AM
Schedule::command('invoices:check-recurring')
    ->dailyAt('09:00')
    ->timezone('Africa/Lagos')
    ->withoutOverlapping()
    ->runInBackground();
