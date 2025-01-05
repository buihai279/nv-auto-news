<?php

use App\Http\Controllers\AddController;
use App\Http\Controllers\Crawler24hController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\HideController;
use App\Http\Controllers\Push2NvgateController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;


Route::get('/', TestController::class)->name('home');
Route::get('/24h', Crawler24hController::class)->name('home2');
Route::get('/push', PushController::class)->name('push');
Route::get('/hide', HideController::class)->name('hide');
Route::get('/detail', DetailController::class)->name('detail');
Route::get('/add', AddController::class)->name('add');
Route::get('/publish-nvgate', Push2NvgateController::class)->name('publish-2-nvgate');