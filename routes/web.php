<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Middleware\ApiOrViewGetRespond;
use App\Http\Middleware\ApiOrViewPostRespond;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthCheckMiddleware;
use App\Http\Middleware\AuthRespond;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ThemeController;

use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('blog.index');
});

Route::get('/dashboard', function () {
     return redirect()->route('blog.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('/blog')->middleware(ApiOrViewGetRespond::class)->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('blog.index')->middleware(ApiOrViewGetRespond::class);
    Route::get("/{topic}/sort", [ ThemeController::class, 'sort'])->name('topics.comments.sort');
    Route::get("/search", [ IndexController::class, 'search'])->name('topics.search');
    Route::get("/{topic}", [ThemeController::class, 'index'])->name('topics.get.one');
});

Route::prefix('/comments')->middleware([AuthCheckMiddleware::class, ApiOrViewPostRespond::class])->group(function () {
    Route::post('/{topic}', [CommentController::class, 'left'])->name('comments.left');
    Route::delete('/{comment}', [CommentController::class, 'delete'])->name('comments.delete');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

Route::get('/messenger/{receiver}', [MessengerController::class, 'getListOfUsers'])->middleware([AuthCheckMiddleware::class, ApiOrViewGetRespond::class,])->name('messenger');

Route::prefix('/messages')->middleware([AuthCheckMiddleware::class, ApiOrViewPostRespond::class])->group(function () {
    Route::post('/{receiver}', [MessengerController::class, 'send'])->name('messages.left');
    Route::delete('/{message}', [MessengerController::class, 'delete'])->name('messages.delete');
    Route::put('/{message}', [MessengerController::class, 'update'])->name('messages.update');
    Route::patch('/{message}', [MessengerController::class, 'update'])->name('messages.update');
});

Route::get('/notifications', [NotificationController::class, 'getList'])->name('notifications.list')->middleware(AuthCheckMiddleware::class, ApiOrViewGetRespond::class);

Route::prefix('/answers')->middleware([AuthCheckMiddleware::class, ApiOrViewPostRespond::class])->group(function () {
    Route::post('/{comment}/{receiver}', [AnswerController::class, 'create'])->name('answers.create');
    Route::delete('/{answer}', [AnswerController::class, 'delete'])->name('answers.delete');
    Route::put('/{answer}', [AnswerController::class, 'update'])->name('answers.update');
    Route::patch('/{answer}', [AnswerController::class, 'update'])->name('answers.update');
});

require __DIR__.'/auth.php';
