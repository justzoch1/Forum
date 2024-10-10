<?php

use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiOrViewResponse;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/topics', [TopicController::class, 'getList'])->middleware(ApiOrViewResponse::class);
