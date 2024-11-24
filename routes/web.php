<?php

use App\Http\Controllers\AddController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;


Route::get('/', TestController::class)->name('home');
Route::get('/detail', DetailController::class)->name('detail');
Route::get('/add', AddController::class)->name('add');