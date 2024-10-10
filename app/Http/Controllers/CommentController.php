<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentSendRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Theme;
use App\Repositories\CommentRepository;
use App\Services\CommentControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /*
    # Поиск по комментариям.
    # Cортировка коментариев;
    # Возможность листать комментарии до бесконечности;
    */

    /*
    *  Получение списка всех комментариев
    */
    public function getList(CommentRepository $repository): array
    {
        return $repository->getList();

        abort_unless($comments, 500);

        return [
            'status' => 'succes',
            'items' => $comments
        ];
    }

    /*
    *  Фильтрация комментариев по темам
    */
    public function getListOfTopic(Theme $topic, CommentRepository $repository): array
    {
        $comments = $repository->getListOfTopic($topic);

        abort_unless($comments, 500);

        return [
            'status' => 'succes',
            'items' => $comments
        ];
    }

    /*
    * Отправка нового сообщения
    */
    public function create(CommentSendRequest $request, CommentControllerService $service): array
    {
        $comment = $service->create($request->all());

        abort_unless($comment, 404);

        return [
            'status' => 'succes',
            'item' => $comment
        ];
    }

    /*
    * Удаление сообщения
    */
    public function delete(Comment $comment, CommentControllerService $service): array
    {
        $comment = $service->delete($comment);

        return [
            'status' => 'succes',
        ];
    }

    /*
    * Редактирование сообщения
    */
    public function update(Comment $comment, CommentUpdateRequest $request, CommentControllerService $service): array
    {
        Log::info($request->all);
        $comment = $service->update($comment, $request->all());

        return [
            'status' => 'succes',
            'item' => $comment
        ];
    }
}
