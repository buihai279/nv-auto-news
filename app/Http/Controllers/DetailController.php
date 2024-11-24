<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailSKDSNews;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\CrawlQueue\DetailCrawlQueue;
use App\Models\CrawlerUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Spatie\Crawler\Crawler;

class DetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $url = $request->url;
        $crawler = CrawlerUrl::where('url', $url)->first();
        if ($crawler->html) {
            return view('dashboard.detail', ['html' => $crawler->html]);
        }
        $queue = app(DetailCrawlQueue::class);

        Crawler::create()
            ->setCrawlQueue($queue)
            ->setCurrentCrawlLimit(2)
            ->setMaximumDepth(2)
            ->setCrawlObserver(new CrawleDetailSKDSNews())
            ->startCrawling($url);

        $crawler = CrawlerUrl::where('url', $url)->first();
        if ($crawler->html) {
            return view('dashboard.detail', ['html' => $crawler->html]);
        }
    }
}
