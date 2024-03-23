<?php

use App\Jobs\JobsDailyRecordRunner;
use App\Jobs\JobsUserRunner;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Invoke User Jobs.
Schedule::job(new JobsUserRunner)->hourly();
// Invoke Daily Jobs.
Schedule::job(new JobsDailyRecordRunner)->daily();