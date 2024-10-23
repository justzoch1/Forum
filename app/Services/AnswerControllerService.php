<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class AnswerControllerService
{
    public function createFromRequest(array $data, Comment $comment, User $receiver, User $author): Answer
    {
        $answer = Answer::create(array_merge($data, [
            'theme_id' => $comment->theme_id,
            'comment_id' => $comment->id,
            'user_id' => $author->id,
            'receiver_id' => $receiver->id
        ]));

        Log::info($answer);

        return $answer;
    }

    public function updateFromRequest(Answer $answer, array $data): Answer
    {
        $answer->update($data);

        return $answer;
    }

    public function delete(Answer $answer): void
    {
        $answer->delete();
    }
}
