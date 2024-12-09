<?php

namespace App\Http\Controllers;

use App\Models\CrawlerUrl;
use Illuminate\Support\Str;
use voku\helper\HtmlDomParser;

class Crawler24hController extends Controller
{
    public function __invoke()
    {
        $list = [
            'https://24h.24hstatic.com/ajax/box_template_tin_bai_noi_bat_khac/index/76/1/14/0/0/0/0?v_is_ajax=1&v_device_global=pc&v_max_row=214&fk_listing_template=855&pk_listing_template_box=10494&v_type_box_template=tin_bai_noi_bat_khac&v_show_date=0&v_show_event=0&p_date=&v_view=6&t=1733246373',
            'https://24h.24hstatic.com/ajax/box_bai_viet_trang_su_kien/index/76/3910/1/255/1/4/0/0/0/0?v_count_page_1=12',
            'https://24h.24hstatic.com/ajax/box_template_tin_bai_noi_bat_khac/index/460/1/14/0/0/0/0?v_is_ajax=1&v_device_global=pc&v_max_row=224&fk_listing_template=850&pk_listing_template_box=10453&v_type_box_template=tin_bai_noi_bat_khac&v_show_date=0&v_show_event=0&p_date=&v_view=6&t=1733330464'
        ];
        foreach ($list as $item) {

            $html = file_get_contents($item);
            $dom = HtmlDomParser::str_get_html($html);
            foreach ($dom->find('article') as $a) {
                $url = $a->findOne('a')->getAttribute('href');
                $src = $a->findOne('img')->getAttribute('src');
                if (!Str::contains($url, 'https')) {
                    $src = $a->findOne('img')->getAttribute('data-original');
                }
                $src = Str::replace('255x170/', '', $src);
                CrawlerUrl::updateOrCreate([
                    'url' => $url
                ], [
                    'url' => $url,
                    'title' => html_entity_decode(trim($a->findOne('header p a')->text())),
                    'site' => '24h',
                    'thumbnail' => $src
                ]);
            }
        }
        return view('dashboard.main', ['newss' => CrawlerUrl::orderByDesc('id')->paginate(10)]);
    }
}
