<?php

namespace App\CrawlObserver;

use App\Models\CrawlerUrl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use voku\helper\HtmlDomParser;

class CrawleDetailNews extends CrawlObserver
{
    public function willCrawl($url, ?string $linkText): void
    {
//                    echo "Crawling: $url" . PHP_EOL;
    }

    public function crawled($url, $response, UriInterface|\Psr\Http\Message\UriInterface|null $foundOnUrl = null, ?string $linkText = null): void
    {
        Log::info('Crawled: ' . $url);
        $html = $response->getCachedBody();
        $html = HtmlDomParser::str_get_html($html);
        if (Str::contains($url, 'suckhoedoisong')) {
            $this->suckhoe($url, $html);
        }
        if (Str::contains($url, 'znews')) {
            $this->znews($url, $html);
        }
        if (Str::contains($url, 'vnexpress')) {
            $this->vnexpress($url, $html);
        }
        if (Str::contains($url, 'www.24h.com.vn/') && $url != 'https://www.24h.com.vn/') {
            $this->_24h($url, $html);
        }

    }

    public function suckhoe($url, $dom)
    {
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

    public function vnexpress($url, $dom)
    {
        $dom = HtmlDomParser::str_get_html($dom->findOne('.page-detail .container .fck_detail ')->outerhtml);
        $domOrigin = $dom;
        echo $html = $dom->outerhtml;
        die;
    }

    public function _24h($url, $dom)
    {
        $dom = HtmlDomParser::str_get_html($dom->findOne('.cate-24h-foot-arti-deta-info')->outerhtml);
        foreach ($dom->find('img') as $a) {
            $curFile = $a->getAttribute('src');
            if (Str::contains($a->getAttribute('src'), 'data:image')) {
                $a->setAttribute('src', $a->getAttribute('data-original'));
                $curFile = $a->getAttribute('data-original');
                $a->removeAttribute('data-original');
            }
            $file = $file ?? $curFile;
        }
        foreach ($dom->find('span') as $a) {
            $a->removeAttribute('style');
        }
        foreach ($dom->find('a') as $a) {
            $a->removeAttribute('style');
        }
        foreach ($dom->find('img') as $a) {
            $a->removeAttribute('style');
            $a->removeAttribute('onclick');
        }
        foreach ($dom->find('div') as $a) {
            $a->removeAttribute('style');
        }
        foreach ($dom->find('script') as $a) {
            $a->delete();
        }

        $dom->findOne('.bv-lq')->delete();
        $dom->findOne('.linkOrigin')->delete();
        $dom->findOne('#zone_banner_sponser_product')->delete();
        $dom->findOne('#24h-banner-in-image')->delete();
        echo $html = $dom->outerhtml;
        $crawler = CrawlerUrl::firstWhere('url', $url);
        if ($crawler) {
            $crawler->html=$html;
            $crawler->save();
        } else {
            CrawlerUrl::create([
                'url' => $url,
                'site' => '24h',
                'html' => $html,
            ]);
        }
    }

    public function znews($url, $dom)
    {
        $domOrigin = $dom;
        $dom = HtmlDomParser::str_get_html($dom->findOne('.the-article-body')->outerhtml);
        foreach ($dom->find('img') as $a) {
            $a->setAttribute('src', $a->getAttribute('data-src'));
            $a->removeAttribute('data-src');
        }
        foreach ($dom->findMulti('td') as $tr) {
            foreach ($tr->findMulti('.mobile') as $spanDelete) {
                $tr->outerhtml = '';
            }
        }
        $dom->save();
        $dom->findOne('table.article')->delete();
        $dom->findOne('table.article')->delete();
        $dom->findOne('table.article')->delete();
        $dom->findOne('table.article')->delete();
        $dom->findOne('table.article')->delete();
        $dom->findOne('table.article')->delete();
        $dom->findOne('div.notebox.ncenter')->delete();
        echo $html = $dom->findOne('.the-article-body')->outerhtml;
        CrawlerUrl::updateOrCreate([
            'url' => $url,
            'site' => 'znews',
        ], [
            'html' => $html,
            'title' => $domOrigin->findOne('.the-article-title')->text(),
        ]);
    }

}