<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailNews;
use App\Models\CrawlerUrl;
use App\Services\CepCmsBalodiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Crawler\Crawler;
use voku\helper\HtmlDomParser;

class DetailController extends Controller
{
    public $id = "5f785eb27479e164987b18960632d962637069a339e7d386a7c8597668a3e88c";
public function __construct(protected CepCmsBalodiService $cepCmsBalodiService)
{
}

    public function __invoke(Request $request)
    {
        foreach (CrawlerUrl::where('site', '24h')->where('balodi_category_id',1001)->limit(300)->get() as $crawler) {
            $this->cepCmsBalodiService->sendContentPostBalody($crawler);

//            //2
//            Crawler::create()
////            ->setCrawlQueue($queue)
//                ->setCurrentCrawlLimit(2)
//                ->setMaximumDepth(2)
//                ->setCrawlObserver(new CrawleDetailNews())
//                ->startCrawling($crawler->url);
        }die;
        $url = $request->url;
        $crawler = CrawlerUrl::where('url', $url)->first();
//        $this->cepCmsBalodiService->search($crawler->title);
//die;
        if (empty($crawler->html)) {
            Crawler::create()
//            ->setCrawlQueue($queue)
                ->setCurrentCrawlLimit(2)
                ->setMaximumDepth(2)
                ->setCrawlObserver(new CrawleDetailNews())
                ->startCrawling($url);
        }
        if ($crawler->site == '24h') {
            $this->cepCmsBalodiService->sendContentPostBalody($crawler);
            if ($crawler->html) {
                return view('dashboard.detail', ['html' => $crawler->html]);
            }
        }
    }


}
