<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Theme;
use App\Models\User;
use App\Services\AnswerControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{

    /*
    * Оставить ответ
    */
    public function create(Comment $comment, User $receiver, Request $request, AnswerControllerService $service): array
    {
        Gate::authorize('create', Answer::class);
        $answer = $service->createFromRequest($request->all(), $comment, $receiver, Auth::user());

        Cache::forget("comments_{$comment->theme_id}");
        return [
            'status' => 'success',
            'comment' => $answer,
        ];
    }

    /*
    * Редактировать ответ
    */
    public function update(Answer $answer, Request $request, AnswerControllerService $service): array
    {
        Gate::authorize('update', $answer);
        $answer = $service->updateFromRequest($answer, $request->all());

        Cache::forget("comments_{$answer->comment->theme_id}");
        return [
            'status' => 'success',
            'comment' => $answer
        ];
    }

    /*
    * Удаление ответ
    */
    public function delete(Answer $answer, AnswerControllerService $service): array
    {
        Gate::authorize('delete', $answer);
        $service->delete($answer);

        Cache::forget("comments_{$answer->comment->theme_id}");
        return [
            'status' => 'success',
        ];
    }
}
