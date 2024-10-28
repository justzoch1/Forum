<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentSendRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Theme;
use App\Services\CommentControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
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
        $comment = $service->createFromRequest($request->validated(), $topic, Auth::user());

        Cache::forget("comments_{$topic->id}");
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

        Cache::forget("comments_{$comment->theme_id}");
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

        Cache::forget("comments_{$comment->theme_id}");
        Log::info("Comment deleted ");
        return [
            'status' => 'success',
        ];
    }
}
