<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawlerDetailNews;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\Models\CrawlerUrl;
use Spatie\Crawler\Crawler;

class TestController extends Controller
{
    public function __invoke()
    {
//        Crawler::create()
//            ->setTotalCrawlLimit(1)
//            ->setMaximumDepth(2)
//            ->setConcurrency(1)
//            ->setCrawlObserver(new CrawlerListSKDSNews())
//            ->startCrawling('https://suckhoedoisong.vn/gioi-tinh.htm');
//        $news = CrawlerUrl::where('is_crawled', false)->get();
//        foreach ($news as $new) {
//            Crawler::create()
//                ->setTotalCrawlLimit(1)
//                ->setMaximumDepth(2)
//                ->setConcurrency(1)
//                ->setCrawlObserver(new CrawlerDetailNews())
//                ->startCrawling($new->url);
//            $new->is_crawled = true;
//            $new->save();
//        }
        return view('dashboard.main', ['newss' => CrawlerUrl::get()]);
    }
}
