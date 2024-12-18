<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
use voku\helper\HtmlDomParser;

class CrawlerListVNENews extends CrawlObserver
{
    //const domain
    public $domain = 'https://vnexpress.net/';
    public $sitename = 'vnexpress';

    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {

        $html = $response->getCachedBody();

        $dom = HtmlDomParser::str_get_html($html);
        foreach ($dom->find('.item-news') as $a) {
            $url = $a->findOne('a')->getAttribute('href');
            $linkText = $a->findOne('.title-news')->text();
            if (empty($url)||empty($linkText)) {
                continue;
            }
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