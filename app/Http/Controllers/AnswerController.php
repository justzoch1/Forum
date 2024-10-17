<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\User;
use App\Models\Theme;
use App\Services\AnswerControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    /*
    * Оставить ответ
    */
    public function create(Theme $topic, Comment $comment, Request $request, AnswerControllerService $service): array
    {
        Gate::authorize('create', Answer::class);
        $answer = $service->createFromRequest($request->all(), $topic, $comment, $this->user);
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
        return [
            'status' => 'success',
        ];
    }
}
