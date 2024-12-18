<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawlerUrl extends Model
{
    protected $fillable = [
        'url',
        'title',
        'site',
        'html',
        'thumbnail',
        'balodi_id',
        'nvgate_publish_at',
        'vfilm_publish_at',
        'mfilm_publish_at',
        'balodi_publish_at',
    ];
    protected $table = 'crawler_urls';
}
