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

class Push2NvgateController extends Controller
{
    public function __invoke(Request $request)
    {
        $id = $request->input('id');
        $detail = CrawlerUrl::find($id);
        $thumb = $detail->thumbnail;
        //http file
        $file = file_get_contents($thumb);
        $fileName = basename($thumb);
        $response = Http::attach('image', $file, rand(1, 1000) . $fileName)->post('http://app.nvgate.vn/8004/');
        dd($response->json()['path']);

    }
}
