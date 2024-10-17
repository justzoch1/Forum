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
    public function createFromRequest(array $data, Theme $topic, Comment $comment, User $user): Answer
    {
        $answer = Answer::create(array_merge($data, [
            'theme_id' => $topic->id,
            'comment_id' => $comment->id,
            'user_id' => $user->id,
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
