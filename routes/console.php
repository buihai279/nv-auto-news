<?php

use App\Jobs\CrawlerListingJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();
Schedule::job(new CrawlerListingJob())->everyThirtySeconds();

