<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentSendRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Theme;
use App\Services\CommentControllerService;
use Illuminate\Http\Request;

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
        $comment = $service->createFromRequest($request->validated(), $topic);
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
        $service->delete($comment);
        return [
            'status' => 'success',
        ];
    }

    /*
    * Искать по ключевым словами и фразам контента
    */
    public function search(Theme $topic, Request $request, CommentControllerService $service): array
    {
        $comments = $service->search($topic, $request->q);
        return [
            'status' => 'success',
            'items' => $comments,
            'topic' => $topic
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
