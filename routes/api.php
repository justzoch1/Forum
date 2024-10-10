<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\ApiOrViewResponse;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/topics', [TopicController::class, 'getList'])->middleware(ApiOrViewResponse::class);
Route::get('/topics/{topic}/comments', [CommentController::class, 'getListOfTopic'])->middleware(ApiOrViewResponse::class);
Route::post('/comments', [CommentController::class, 'create']);
Route::delete('/comments/{comment}', [CommentController::class, 'delete']);
Route::put('/comments/{comment}', [CommentController::class, 'update']);
Route::patch('/comments/{comment}', [CommentController::class, 'update']);

