<?php

namespace App\Services;

use App\Models\CrawlerUrl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use voku\helper\HtmlDomParser;

class CepCmsBalodiService
{
    public $JSESSIONID = "JSESSIONID=5C763F81321935E1B1789B09FA2FD3A5; JSESSIONID=5C763F81321935E1B1789B09FA2FD3A5";

    public function sendContentPostBalody(CrawlerUrl $crawler)
    {
        $url = 'https://cepcms.vnptvas.vn/content_post.html';
//        if (!empty($crawler->balodi_id)) {
//            $url = "https://cepcms.vnptvas.vn/$crawler->balodi_id";
//            $txtAnhMinhHoa = $this->getImage($crawler->balodi_id);
//        }else{
        $txtAnhMinhHoa = trim($this->uploadFile($crawler->thumbnail));
//        }
        $data = [
            'txtName' => $crawler->title,
            'txtStatus' => '1',
            'txtDes' => Str::limit(trim(strip_tags($crawler->html)), 250),
            'txtContent' => trim($crawler->html), // Rút gọn nội dung
            'txtThoiGian' => $crawler->balodi_publish_at?->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i'),
            'txtAnhMinhHoa' => $txtAnhMinhHoa,
            'txtTheLoai' => $crawler->balodi_category_id,
            'txtPrice' => '0',
            'txtAnhMinhHoa2' => '',
            'txtFileUpload' => '',
            'txtTimeVideo' => '0',
            'txtLiveLink' => '',
            'txtAuthor' => '',
            'txtTap' => '0',
            'btnUpdate' => '',
            'txtGoiCuoc' => '1011284'
        ];

        $response = $this->httpPost($url, $data);
        // Trả về response
        Log::info($response);
        $this->listing($crawler->title);
//        echo  $response;die;
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

    public function search($name)
    {
        $html = HtmlDomParser::str_get_html(file_get_contents(app_path("1.html")));
        foreach ($html->find("#ShowListContent td.align-middle") as $a) {
            $tag = $a->findOne("a[data-target='#InfoForm']");
            $tagLink = $a->findOne("a.btn-icon");
            $href = $a->findOne("a.btn-icon")->getAttribute('href');

            if (!empty($tag->text())) {
//                $id = Str::after(Str::before($tag->html, "','"), "('");
                $title = $tag->text();
            }
            if (!empty($tagLink->text()) && $href !== '#' && !empty($title)) {
                $crawler = CrawlerUrl::firstWhere('title', $title);
                $link = Str::after(Str::before($tagLink->html, '" class='), '"');
                if ($crawler) {
                    $crawler->update([
                        'balodi_id' => $link,
                    ]);
                }
            }

        }

    }

    private function httpPost($url, $data)
    {
        $response = Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Origin' => 'https://cepcms.vnptvas.vn',
                'Referer' => $url,
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
                'sec-ch-ua' => '"Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
                'sec-ch-ua-mobile' => '?0',
                'Cookie' => $this->JSESSIONID,
                'sec-ch-ua-platform' => '"macOS"',
            ])
            ->post($url, $data);
        return $response->body();
    }


    public function getImage($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://cepcms.vnptvas.vn/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language: en-US,en;q=0.9',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Cookie: JSESSIONID=5C763F81321935E1B1789B09FA2FD3A5',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/ ' . rand(100, 200) . '.0.0.0 Safari/537.36',
            'sec-ch-ua: "Google Chrome";v="' . rand(100, 200) . '", "Chromium";v="' . rand(100, 200) . '", "Not_A Brand";v="' . rand(10, 30) . '"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "macOS"',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        Log::info($response);
        return HtmlDomParser::str_get_html($response)->findOne('#txtAnhMinhHoa')->getAttribute('value');
    }

    public function listing($newTitle)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://cepcms.vnptvas.vn/content_list.html");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language: en-US,en;q=0.9',
            'Connection: keep-alive',
            'Cookie: ' . $this->JSESSIONID,
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/
            ' . rand(100, 200) . '.0.0.0 Safari/537.36',
            'sec-ch-ua: "Google Chrome";v="' . rand(100, 200) . '", "Chromium";v="' . rand(100, 200) . '", "Not_A Brand";v="' . rand(10, 30) . '"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "macOS"',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $html = HtmlDomParser::str_get_html($response);
        foreach ($html->find("#ShowListContent td.align-middle") as $a) {
            $tag = $a->findOne("a[data-target='#InfoForm']");
            $tagLink = $a->findOne("a.btn-icon");
            $href = $a->findOne("a.btn-icon")->getAttribute('href');

            if (!empty($tag->text())) {
//                $id = Str::after(Str::before($tag->html, "','"), "('");
                $title = $tag->text();
            }
            if (!empty($tagLink->text()) && $href !== '#' && !empty($title) && $title == $newTitle) {
                $crawler = CrawlerUrl::firstWhere('title', $title);
                $link = Str::after(Str::before($tagLink->html, '" class='), '"');
                if ($crawler) {
                    $crawler->balodi_id = $link;
                    $crawler->save();
                }
                break;
            }
        }

    }
}