<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentSendRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Theme;
use App\Services\CommentControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    /*
    *  Отфильтровать комментариии по темам
    */
    public function getListOfTopic(Theme $topic, CommentControllerService $service): array
    {
        $comments = $service->getListOfTopic($topic);
        return [
            'status' => 'success',
            'items' => $comments,
            'topic' => $topic
        ];
    }

    /*
    * Оставить комментарий
    */
    public function left(Theme $topic, CommentSendRequest $request, CommentControllerService $service): array
    {
        Gate::authorize('create', Comment::class);
        $comment = $service->createFromRequest($request->validated(), $topic, $this->user);
        return [
            'status' => 'success',
            'comment' => $comment,
        ];
    }

    /*
    * Редактировать сообщение
    */
    public function update(Comment $comment, CommentUpdateRequest $request, CommentControllerService $service): array
    {
        Gate::authorize('update', $comment);
        $comment = $service->updateFromRequest($comment, $request->validated());
        return [
            'status' => 'success',
            'comment' => $comment
        ];
    }

    /*
    * Удаление сообщение
    */
    public function delete(Comment $comment, CommentControllerService $service): array
    {
        Gate::authorize('delete', $comment);
        $service->delete($comment);
        return [
            'status' => 'success',
        ];
    }

    /*
    * Сортировать по популярности и дате загрузки
    */
    public function sort(Theme $topic, Request $request, CommentControllerService $service): array
    {
        $comments = $service->sort($topic, $request->by);
        return [
            'status' => 'success',
            'items' => $comments,
            'topic' => $topic
        ];
    }
}
