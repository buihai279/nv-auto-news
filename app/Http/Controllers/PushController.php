<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailNews;
use App\Enum\BalodiCategoryIdEnum;
use App\Models\CrawlerUrl;
use App\Services\CepCmsBalodiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Crawler\Crawler;
use voku\helper\HtmlDomParser;

class PushController extends Controller
{
    public function __construct(protected CepCmsBalodiService $cepCmsBalodiService)
    {
    }

    public function __invoke(Request $request)
    {
        $crawler = CrawlerUrl::where('url', $request->url)->first();
        $this->cepCmsBalodiService->sendContentPostBalody($crawler);
        return redirect()->back();
    }


}
