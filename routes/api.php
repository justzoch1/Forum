<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\ApiOrViewGetRespond;
use App\Http\Middleware\ApiOrViewPostRespond;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/topics')->middleware(ApiOrViewGetRespond::class)->group(function () {
    Route::get('/', [TopicController::class, 'getList'])->name('topics.list');
    Route::prefix('/{topic}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'getListOfTopic'])->name('comments.list');
        Route::get("/search", [ CommentController::class, 'search'])->name('topics.comments.search');
        Route::get("/sort/{by}", [ CommentController::class, 'sort'])->name('topics.comments.sort');
    });
});

Route::prefix('/comments')->middleware(ApiOrViewPostRespond::class)->group(function () {
    Route::post('/{topic}', [CommentController::class, 'left'])->name('comments.left');
    Route::delete('/{comment}', [CommentController::class, 'delete'])->name('comments.delete');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('comments.update');
});
