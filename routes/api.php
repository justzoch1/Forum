<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\ApiOrViewResponse;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/topic', [TopicController::class, 'getList'])->middleware(ApiOrViewResponse::class);
