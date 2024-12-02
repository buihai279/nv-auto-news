<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailSKDSNews;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\CrawlQueue\DetailCrawlQueue;
use App\Models\CrawlerUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Spatie\Crawler\Crawler;
use voku\helper\HtmlDomParser;

class DetailController extends Controller
{
    public $JSESSIONID = "JSESSIONID=FC527FA3AFC82D89A963D2453EEB6CE9; JSESSIONID=FC527FA3AFC82D89A963D2453EEB6CE9";
    public $id = "5f785eb27479e164987b18960632d962637069a339e7d386a7c8597668a3e88c";

    public function __invoke(Request $request)
    {
        foreach (CrawlerUrl::where('site', '24h')->get() as $crawler) {
            $this->sendContentPostBalody($crawler);
        }die;
        $url = $request->url;
        $crawler = CrawlerUrl::where('url', $url)->first();
        if (empty($crawler->html)) {
            Crawler::create()
//            ->setCrawlQueue($queue)
                ->setCurrentCrawlLimit(2)
                ->setMaximumDepth(2)
                ->setCrawlObserver(new CrawleDetailSKDSNews())
                ->startCrawling($url);
        }
        if ($crawler->site == '24h') {
            $this->sendContentPostBalody($crawler);
            if ($crawler->html) {
                return view('dashboard.detail', ['html' => $crawler->html]);
            }
        }
    }

    public function sendContentPostBalody($crawler)
    {

        $url = 'https://cepcms.vnptvas.vn/content_post.html';
        $data = [
            'txtName' => $crawler->title,
            'txtStatus' => '1',
            'txtDes' => Str::limit(trim(strip_tags($crawler->html)), 250),
            'txtContent' => trim($crawler->html), // Rút gọn nội dung
            'txtThoiGian' => date('d-m-Y H:i'),
            'txtAnhMinhHoa' => trim($this->uploadFile($crawler->thumbnail)),
            'txtPrice' => '0',
            'txtAnhMinhHoa2' => '',
            'txtFileUpload' => '',
            'txtTimeVideo' => '0',
            'txtLiveLink' => '',
            'txtAuthor' => '',
            'txtTap' => '0',
            'btnUpdate' => '',
        ];
        // Cấu hình cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Dữ liệu form-urlencoded
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Lấy kết quả trả về
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://cepcms.vnptvas.vn',
            'Referer: https://cepcms.vnptvas.vn/content_post.html',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
            'sec-ch-ua: "Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "macOS"',
            'Cookie: ' . $this->JSESSIONID,
        ]);

        // Thực hiện request
        $response = curl_exec($ch);

        // Kiểm tra lỗi cURL
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error_msg], 500);
        }
        // Đóng cURL
        curl_close($ch);
        // Trả về response
     Log::info($response);
    }

    public function uploadFile($thumbnail)
    {
        //cache
        return Cache::remember($thumbnail, 60 * 60 * 24, function () use ($thumbnail) {

            $url = 'https://cepcms.vnptvas.vn/upload_img?sessionkey=' . $this->getSessionId();
            // Headers cần gửi
            $headers = [
                'Accept' => '*/*',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Cookie' => $this->JSESSIONID,
                'Origin' => 'https://cepcms.vnptvas.vn',
                'Pragma' => 'no-cache',
                'Referer' => 'https://cepcms.vnptvas.vn/content_post.html',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/' . rand(100, 200) . '.0.0.0 Safari/537.36',
                'X-Requested-With' => 'XMLHttpRequest',
                'sec-ch-ua' => '"Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => '"macOS"',
            ];
            $fileName = explode('/', $thumbnail);
            $a = Http::withHeaders($headers)
                ->attach('myfile', file_get_contents($thumbnail), $fileName[count($fileName) - 1])
                ->post($url, [
                    'btnUpdate' => '',
                ]);
            return $a->body();
        });


    }

    public function getSessionId()
    {
        $request = Http::withHeaders([
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Cookie' => 'JSESSIONID=' . $this->JSESSIONID,
            'Pragma' => 'no-cache',
            'Referer' => 'https://cepcms.vnptvas.vn/content_list.html',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-User' => '?1',
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/' . rand(100, 200) . '.0.0.0 Safari/537.36',
            'sec-ch-ua' => '"Google Chrome";v="' . rand(100, 200) . '", "Chromium";v="' . rand(100, 200) . '", "Not_A Brand";v="' . rand(10, 30) . '"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"macOS"',
        ])->get('https://cepcms.vnptvas.vn/content_post.html');
        $dom = HtmlDomParser::str_get_html($request->body());
        return Str::after($dom->findOne('#formuploadimg')->getAllAttributes()['action'], 'upload_img?sessionkey=');
    }
}
