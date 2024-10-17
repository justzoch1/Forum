<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Middleware\ApiOrViewGetRespond;
use App\Http\Middleware\ApiOrViewPostRespond;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthRespond;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', [TopicController::class, 'getList'])->name('api.topics.list')->middleware(ApiOrViewGetRespond::class);

Route::prefix('/auth')->middleware(AuthRespond::class)->group(function() {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'token'])->name('api.auth.login');
});

Route::prefix('/forum')->middleware(ApiOrViewGetRespond::class)->group(function () {
    Route::prefix('/{topic}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'getListOfTopic'])->name('api.comments.list');
        Route::get("/search", [ CommentController::class, 'search'])->name('api.topics.comments.search');
        Route::get("/sort", [ CommentController::class, 'sort'])->name('api.topics.comments.sort');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/comments')->middleware(ApiOrViewPostRespond::class)->group(function () {
        Route::post('/{topic}', [CommentController::class, 'left'])->name('api.comments.left');
        Route::delete('/{comment}', [CommentController::class, 'delete'])->name('api.comments.delete');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('api.comments.update');
        Route::patch('/{comment}', [CommentController::class, 'update'])->name('api.comments.update');
    });

    Route::get('/messenger/{receiver}', [MessengerController::class, 'getListOfUsers'])->middleware(ApiOrViewGetRespond::class);

    Route::prefix('/messages')->middleware(ApiOrViewPostRespond::class)->group(function () {
        Route::post('/{receiver}', [MessengerController::class, 'send'])->name('api.messages.left');
        Route::delete('/{message}', [MessengerController::class, 'delete'])->name('api.messages.delete');
        Route::put('/{message}', [MessengerController::class, 'update'])->name('api.messages.update');
        Route::patch('/{message}', [MessengerController::class, 'update'])->name('api.messages.update');
    });

    Route::get('/notifications', [NotificationController::class, 'getList'])->name('api.notifications.list')->middleware(ApiOrViewGetRespond::class);

    Route::prefix('/answers')->middleware(ApiOrViewPostRespond::class)->group(function () {
        Route::post('/{topic}/{comment}', [AnswerController::class, 'create'])->name('api.answers.create');
        Route::delete('/{answer}', [AnswerController::class, 'delete'])->name('api.answers.delete');
        Route::put('/{answer}', [AnswerController::class, 'update'])->name('api.answers.update');
        Route::patch('/{answer}', [AnswerController::class, 'update'])->name('api.answers.update');
    });
});
