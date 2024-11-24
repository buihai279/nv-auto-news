<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
use voku\helper\HtmlDomParser;

class CrawleDetailSKDSNews extends CrawlObserver
{
    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {
        $html = $response->getCachedBody();
        $dom = HtmlDomParser::str_get_html($html);
        $html = $dom->findOne('div.detail-content')->outerhtml;

        $dom = HtmlDomParser::str_get_html($html);
        $dom->findOne('div[type=RelatedOneNews]')->delete();
        $dom->findOne('div[type=RelatedOneNews]')->delete();
        $dom->findOne('div[type=VideoStream]')->delete();
        $dom->findOne('div[type=VideoStream]')->delete();
        $html = $dom->html();
        $html = Str::replace('<h3>Mời độc giả xem thêm:</h3>', '', $html);
        $html = Str::replace('<h4>Mời bạn đọc xem tiếp video:</h4>', '', $html);
        $html = Str::replace('<p><b>Xem thêm video đang được quan tâm:</b></p>', '', $html);
        $html = Str::replace('<h3>Xem thêm video đang được quan tâm:</h3>', '', $html);
        CrawlerUrl::updateOrCreate([
            'url' => $url,
            'site' => 'suckhoedoisong',
        ], [
            'html' => $html,
        ]);

    }
}