<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\UpdateStockPricesJob;
use App\Console\Commands\SendGreetingEmails;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new UpdateStockPricesJob)->hourly();

// Daily at 8 AM
Schedule::command(SendGreetingEmails::class)->dailyAt('08:00');