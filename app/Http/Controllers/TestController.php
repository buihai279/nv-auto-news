<?php

namespace App\Http\Controllers;

use App\CrawlObserver\CrawleDetailNews;
use App\CrawlObserver\CrawlerList24h;
use App\CrawlObserver\CrawlerListLink24h;
use App\CrawlObserver\CrawlerListSKDSNews;
use App\CrawlObserver\CrawlerListVNENews;
use App\CrawlObserver\CrawlerListZingNews;
use App\Enum\BalodiCategoryIdEnum;
use App\Models\CrawlerUrl;
use Illuminate\Http\Request;
use Spatie\Crawler\Crawler;
use voku\helper\HtmlDomParser;

class TestController extends Controller
{
    public function __invoke(Request $request)
    {

//        $time = strtotime(date('Y-12-01 H:i:s'));
//        foreach (CrawlerUrl::get() as $crawler) {
//            $time = $time + 14400;
//            $date = date('Y-m-d H:i:s', $time);
//            $crawler->balodi_publish_at = $date;
//            $crawler->save();
//        }
//        die;
//          $html = file_get_contents('https://24h.24hstatic.com/ajax/box_template_tin_bai_noi_bat_khac/index/76/1/14/0/0/0/0?v_is_ajax=1&v_device_global=pc&v_max_row=214&fk_listing_template=855&pk_listing_template_box=10494&v_type_box_template=tin_bai_noi_bat_khac&v_show_date=0&v_show_event=0&p_date=&v_view=6&t=1733246373');
//        $dom = HtmlDomParser::str_get_html($html);
//        foreach ($dom->find('article') as $a) {
//            $url = $a->findOne('a')->getAttribute('href');
//            CrawlerUrl::updateOrCreate([
//                'url' => $url
//            ], [
//                'url' => $url,
//                'title' => html_entity_decode(trim($a->findOne('header p a')->text())),
//                'site' => '24h',
//                'thumbnail' => $a->findOne('img')->getAttribute('src')
//            ]);
//        }die;

//        $list1 = [
//            'https://suckhoedoisong.vn/khoe-dep.htm',
//            'https://suckhoedoisong.vn/dinh-duong.htm'
//        ];
//        $list2 = [
//            'https://znews.vn/du-lich.html'
//        ];
//      Crawler::create()
//            ->setTotalCrawlLimit(1)
//            ->setMaximumDepth(2)
//            ->setCrawlObserver(new CrawlerList24h())
//            ->startCrawling('https://www.24h.com.vn/du-lich-24h-c76.html');
//        foreach ($list1 as $item) {
//            Crawler::create()
//                ->setTotalCrawlLimit(1)
//                ->setMaximumDepth(2)
//                ->setCrawlObserver(new CrawlerListSKDSNews())
//                ->startCrawling($item);
//        }
//        foreach ($list2 as $item) {
//            Crawler::create()
//                ->setTotalCrawlLimit(1)
//                ->setMaximumDepth(2)
//                ->setCrawlObserver(new CrawlerListZingNews())
//                ->startCrawling($item);
//        }
        if ($request->get('q')){
            $q = $request->get('q');
            $crawler = CrawlerUrl::where('title', 'like', "%$q%")->paginate(10);
        }else{
            $crawler = CrawlerUrl::orderByDesc('id')->paginate(10);
        }
        return view('dashboard.main', ['categories'=>BalodiCategoryIdEnum::cases(),'newss' => $crawler]);
    }
}
