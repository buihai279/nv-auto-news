<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
use voku\helper\HtmlDomParser;

class CrawlerListZingNews extends CrawlObserver
{
    //const domain
    public $domain = 'https://znews.vn/';
    public $sitename = 'znews';

    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {

        $html = $response->getCachedBody();

        $dom = HtmlDomParser::str_get_html($html);
        foreach ($dom->find('.article-item') as $a) {
            $url = $a->findOne('a')->getAttribute('href');
            $linkText = $a->findOne('.article-title')->text();
            $src = $a->findOne('img')->getAttribute('src');
            if (Str::contains($src, 'data:image')) {
                $src = $a->findOne('img')->getAttribute('data-src');
            }

            if (!Str::contains($url, '-post') || empty($src)) {
                continue;
            }
            CrawlerUrl::updateOrCreate([
                'url' => $url
            ], [
                'url' => $url,
                'title' => trim($linkText),
                'thumbnail' => $src,
                'site' => $this->sitename
            ]);

        }
    }
}