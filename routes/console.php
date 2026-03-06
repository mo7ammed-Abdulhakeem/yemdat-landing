<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Send Event Reminders Daily at 8:00 AM Server Time
Schedule::command('app:send-event-reminders')->dailyAt('08:00');
