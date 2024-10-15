<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiOrViewGetRespond;
use App\Http\Middleware\ApiOrViewPostRespond;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthRespond;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/auth')->group(function() {
    Route::get('/register', function () {
        return view('auth.register');
    });
    Route::get('/login', function () {
        return view('auth.login');
    });
});

Route::prefix('/auth')->middleware(AuthRespond::class)->group(function() {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'token'])->name('auth.token');
});

Route::prefix('/topics')->middleware(ApiOrViewGetRespond::class)->group(function () {
    Route::get('/', [TopicController::class, 'getList'])->name('topics.list');
    Route::prefix('/{topic}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'getListOfTopic'])->name('comments.list');
        Route::get("/search", [ CommentController::class, 'search'])->name('topics.comments.search');
        Route::get("/sort/{by}", [ CommentController::class, 'sort'])->name('topics.comments.sort');
    });
});

Route::prefix('/comments')->middleware(ApiOrViewPostRespond::class)->group(function () {
    Route::post('/', [CommentController::class, 'left'])->name('comments.left');
    Route::delete('/{comment}', [CommentController::class, 'delete'])->name('comments.delete');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('comments.update');
});
