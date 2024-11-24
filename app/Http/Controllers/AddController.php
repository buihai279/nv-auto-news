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
        $detail = CrawlerUrl::firstWhere('url', $request->input('url'))->first();
        $data = [
            'name' => $detail->name,
            'name_ascii' => Str::slug($detail->name),
            'url_alias' => Str::slug($detail->name),
            'description' => $detail->html,
            'short_description' => strip_tags($detail->html),
            'short_description_ascii' => strip_tags($detail->html),
            'schedule_post' => '',
            'categories' => ["57eb186d340715d45edcbe6d"],
            'tags' => [$detail->name],
            'tags_ascii' => [Str::slug($detail->name)],
            'files' => [
                'logo' => [$detail->image]
            ],
            'file_uris' => [
                'logo' => [
                    $detail->image => $detail->image
                ]
            ],
            'feature' => 0,
            'is_slider' => 0,
            'weight' => '',
            'meta_title' => $detail->name,
            'meta_description' => $detail->name,
            'meta_tags' => $detail->name,
            'status' => 1,
            'user' => '62abdf19340715267cb5671c',
            'created' => now(),
            'modified' => now()
        ];
        Http::post('http://127.0.0.1:8002', $data);
    }
}
