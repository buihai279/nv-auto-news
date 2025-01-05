<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'deleted_at',
    ];
    use SoftDeletes;

    protected $table = 'crawler_urls';
    protected $casts = [
        'nvgate_publish_at' => 'datetime',
        'vfilm_publish_at' => 'datetime',
        'mfilm_publish_at' => 'datetime',
        'balodi_publish_at' => 'datetime',
    ];
}
