<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerDetailNews extends CrawlObserver {
    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {
        $html = $response->getCachedBody();



        $crawler = new Crawler($html);

        $h1 = $crawler->filter('h1')->text();
        $content = $crawler->filter('.detail-content')->first()->text();
        dump($h1, $content);
        $sapo = $crawler->filter('.detail-sapo')->first()->outerHtml();

        //xoa 1 doan
        $crawler->filter('.detail-content > *')->each(function (Crawler $node, $i) {
                $node->filter('.toc-list-headings')->each(function (Crawler $node) {
                    $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                });
        });
        $content = $crawler->filter('.detail-content')->first()->html();
        dd($h1, $sapo, $content);
//        $content = $crawler->filter('.content-layout')->text();
       dd($h1, $sapo, $content);

    }
}