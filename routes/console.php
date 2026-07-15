<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Weekly report - every Monday 6 AM, sends previous week's summary to Admin
Schedule::command('report:generate-weekly')->weeklyOn(1, '06:00');
