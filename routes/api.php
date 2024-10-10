<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\ApiOrViewResponse;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/topics')->group(function () {
    Route::get('/', [TopicController::class, 'getList'])->middleware(ApiOrViewResponse::class);
    Route::prefix('/{topic}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'getListOfTopic'])->middleware(ApiOrViewResponse::class);
        Route::get("/search", [ CommentController::class, 'search']);
        Route::get("/sort/{by}", [ CommentController::class, 'sort']);
    });
});

Route::prefix('/comments')->group(function () {
    Route::post('/', [CommentController::class, 'left']);
    Route::delete('/{comment}', [CommentController::class, 'delete']);
    Route::put('/{comment}', [CommentController::class, 'update']);
    Route::patch('/{comment}', [CommentController::class, 'update']);
});
