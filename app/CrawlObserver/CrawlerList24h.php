<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
use voku\helper\HtmlDomParser;

class CrawlerList24h extends CrawlObserver
{
    public $sitename = '24h';

    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {
        Log::info('Crawled: ' . $url);
        $html = $response->getCachedBody();

        $dom = HtmlDomParser::str_get_html($html);
        foreach ($dom->find('.cate-24h-car-news-rand__info') as $a) {
            $url = $a->findOne('a')->getAttribute('href');
            $linkText = $a->findOne('a')->text();
            CrawlerUrl::updateOrCreate([
                'url' => $url
            ], [
                'url' => $url,
                'title' => trim($linkText),
                'site' => $this->sitename
            ]);

        }
    }
}