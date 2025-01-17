<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailNews;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\Models\CrawlerUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Spatie\Crawler\Crawler;

class AddController extends Controller
{
    public function __invoke(Request $request)
    {

    }
}
