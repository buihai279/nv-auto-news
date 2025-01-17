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
        $logo = $this->uploadFile($detail);
        //57c64a675df311f02100002aXổ số
        //57c64a715df311f02100002bY tế
        //57e5f2bb3407153d4bdccb02Du lịch
        //57eb186d340715d45edcbe6dCông nghệ
        //5cb6fd503407155976d384f8Thế giới VTV
        //test lai local
        $data = [
            "collection_name"=> "news",// Tên collection
            'name' => $detail->title,
            'name_ascii' => Str::slug($detail->title),
            'url_alias' => Str::slug($detail->title),
            'description' => Str::replace('\n', '', $detail->html),
            'short_description' => strip_tags($detail->html),
            'short_description_ascii' => strip_tags('test'),
            'schedule_post' => '',
            'categories' => ["57eb186d340715d45edcbe6d"],
            'tags' => [$detail->title],
            'tags_ascii' => [Str::slug($detail->title)],
            'files' => [
                'logo' => [ $logo]
            ],
            'file_uris' => [
                'logo' => [
                    "671c5edfc0af7e9cf08b4567"=> $logo
                ]
            ],
            'feature' => 0,
            'is_slider' => 0,
            'weight' => '',
            'meta_title' => $detail->title,
            'meta_description' => $detail->title,
            'meta_tags' => $detail->title,
            'status' => 1,
            'user' => '62abdf19340715267cb5671c',
            'created' => now()->toDateString(),
            'modified' => now()->toDateString(),
        ];
//        dump(Http::post('http://app.nvgate.vn/8007')->body());
        //JSON payload
        // Set the content type to application/json
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('http://app.nvgate.vn/8007/', $data);
        $result=json_decode($response->getBody()->getContents());
        if ($result->success) {
            $detail->nvgate_publish_at = now();
            $detail->save();
        }
    }

    private function uploadFile($detail)
    {
        $thumb = $detail->thumbnail;
        $file = file_get_contents($thumb);
        $fileName = basename($thumb);
        $response = Http::attach('image', $file, rand(1, 1000) . $fileName)->post('http://app.nvgate.vn/8004/');
        return Str::replace("/opt/www/nvgate/nvgate_cms/app/",'',$response->json()['path']);
    }
}
