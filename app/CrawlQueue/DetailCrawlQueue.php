<?php

namespace App\CrawlQueue;

use Spatie\Crawler\CrawlQueues\ArrayCrawlQueue;
use Spatie\Crawler\CrawlQueues\CrawlQueue;

class DetailCrawlQueue extends ArrayCrawlQueue
{

    protected array $urls = [];
    protected array $pendingUrls = [];
}