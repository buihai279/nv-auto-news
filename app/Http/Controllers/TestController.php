<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailSKDSNews;
use App\CrawlObserver\CrawlerList24h;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\CrawlObserver\CrawlerListVNENews;
use App\CrawlObserver\CrawlerListZingNews;
use App\Models\CrawlerUrl;
use Spatie\Crawler\Crawler;

class TestController extends Controller
{
    public function __invoke()
    {
        $list1 = [
            'https://suckhoedoisong.vn/khoe-dep.htm',
            'https://suckhoedoisong.vn/dinh-duong.htm'
        ];
        $list2 = [
            'https://znews.vn/du-lich.html'
        ];
        Crawler::create()
            ->setTotalCrawlLimit(1)
            ->setMaximumDepth(2)
            ->setCrawlObserver(new CrawlerList24h())
            ->startCrawling('https://www.24h.com.vn/du-lich-24h-c76.html');
        foreach ($list1 as $item) {
            Crawler::create()
                ->setTotalCrawlLimit(1)
                ->setMaximumDepth(2)
                ->setCrawlObserver(new CrawlerListSKDSNews())
                ->startCrawling($item);
        }
        foreach ($list2 as $item) {
            Crawler::create()
                ->setTotalCrawlLimit(1)
                ->setMaximumDepth(2)
                ->setCrawlObserver(new CrawlerListZingNews())
                ->startCrawling($item);
        }
        return view('dashboard.main', ['newss' => CrawlerUrl::orderByDesc('id')->paginate(10)]);
    }
}
