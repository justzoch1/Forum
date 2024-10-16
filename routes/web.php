<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiOrViewGetRespond;
use App\Http\Middleware\ApiOrViewPostRespond;
use App\Http\Middleware\ApiOrViewResponse;
use \App\Http\Controllers\MessengerController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [TopicController::class, 'getList'])->name('topics.list')->middleware(ApiOrViewGetRespond::class);

Route::prefix('/forum')->middleware(ApiOrViewGetRespond::class)->group(function () {
    Route::prefix('/{topic}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'getListOfTopic'])->name('comments.list');
        Route::get("/search", [ CommentController::class, 'search'])->name('topics.comments.search');
        Route::get("/sort", [ CommentController::class, 'sort'])->name('topics.comments.sort');
    });
});

Route::prefix('/comments')->middleware(ApiOrViewPostRespond::class)->group(function () {
    Route::post('/{topic}', [CommentController::class, 'left'])->name('comments.left');
    Route::delete('/{comment}', [CommentController::class, 'delete'])->name('comments.delete');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

Route::get('/messenger/{sender}/{receiver}', [MessengerController::class, 'getListOfUsers'])->name('messages.list')->middleware(ApiOrViewGetRespond::class);

Route::prefix('/messages')->middleware(ApiOrViewPostRespond::class)->group(function () {
    Route::post('/{sender}/{receiver}', [MessengerController::class, 'send'])->name('messages.left');
    Route::delete('/{message}', [MessengerController::class, 'delete'])->name('messages.delete');
    Route::put('/{message}', [MessengerController::class, 'update'])->name('messages.update');
    Route::patch('/{message}', [MessengerController::class, 'update'])->name('messages.update');
});

Route::get('/notifications/{user}', [NotificationController::class, 'getList'])->name('notifications.list')->middleware(ApiOrViewGetRespond::class);
