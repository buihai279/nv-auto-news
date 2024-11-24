<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawlerListSKDSNews;
use App\Models\CrawlerUrl;
use Spatie\Crawler\Crawler;

class TestController extends Controller
{
    public function __invoke()
    {
        $list =[
            '/khoe-dep.htm',
            '/dinh-duong.htm'
        ];
//        foreach ($list as $item) {
//            Crawler::create()
//                ->setTotalCrawlLimit(1)
//                ->setMaximumDepth(2)
//                ->setConcurrency(1)
//                ->setCrawlObserver(new CrawlerListSKDSNews())
//                ->startCrawling('https://suckhoedoisong.vn'.$item);
//        }
        return view('dashboard.main', ['newss' => CrawlerUrl::orderByDesc('id')->paginate(10)]);
    }
}
