<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailSKDSNews;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\CrawlQueue\DetailCrawlQueue;
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
        //test lai local
        $detail = CrawlerUrl::firstWhere('url', $request->input('url'))->first();
        $data = [
            "collection_name"=> "news",// Tên collection
            'name' => $detail->title,
            'name_ascii' => Str::slug($detail->title),
            'url_alias' => Str::slug($detail->title),
            'description' => Str::replace('\n', '', $detail->html),
            'short_description' => strip_tags('test'),
            'short_description_ascii' => strip_tags('test'),
            'schedule_post' => '',
            'categories' => ["57eb186d340715d45edcbe6d"],
            'tags' => [$detail->title],
            'tags_ascii' => [Str::slug($detail->title)],
            'files' => [
                'logo' => [ "data_files/news_files/image/202410/26/ezgif-com-webp-to-jpg-converter-8-_mpjri.jpg"]
            ],
            'file_uris' => [
                'logo' => [
                    "671c5edfc0af7e9cf08b4567"=> "data_files/news_files/image/202410/26/ezgif-com-webp-to-jpg-converter-8-_mpjri.jpg"
                ]
            ],
            'feature' => 0,
            'is_slider' => 0,
            'weight' => '',
            'meta_title' => $detail->title,
            'meta_description' => 'test',
            'meta_tags' => $detail->title,
            'status' => 1,
            'user' => '62abdf19340715267cb5671c',
            'created' => now()->toDateString(),
            'modified' => now()->toDateString(),
        ];
        dump($data);
        dump(Http::post('http://app.nvgate.vn/8007', $data)->body());
    }
}
