<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerListSKDSNews extends CrawlObserver {
    //const domain
    public $domain = 'https://suckhoedoisong.vn';
    public $sitename = 'suckhoedoisong';
    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {
        $html = $response->getCachedBody();
        //read html, export data
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//a[@class="box-home-focus-link-title"]');

        foreach ($nodes as $node) {
            CrawlerUrl::firstOrCreate([
                'url' => $this->domain . $node->getAttribute('href'),
                'site' =>$this->sitename
            ], [
                'url' => $this->domain . $node->getAttribute('href'),
                'title' => $node->getAttribute('title'),
                'site' =>$this->sitename
            ]);
        }
    }
}