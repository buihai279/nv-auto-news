<?php

namespace App\Jobs;

use App\CrawlObserver\CrawlerListSKDSNews;
use App\Models\CrawlerUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Crawler\Crawler;

class CrawlerListingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Crawler::create()
            ->setTotalCrawlLimit(1)
            ->setMaximumDepth(2)
            ->setConcurrency(1)
            ->setCrawlObserver(new CrawlerListSKDSNews())
            ->startCrawling('https://suckhoedoisong.vn/gioi-tinh.htm');
    }
}
