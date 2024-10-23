<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class CommentControllerService
{
    public function getListOfTopic(Theme $topic): array
    {
        $comments = Comment::where('theme_id', $topic->id)
            ->withAnswers()
            ->withThemeAndUser()
            ->onlyApproved()
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'count' => count($comments),
            'comments' => $comments,
        ];
    }

    public function search(Theme $topic, string $q): array {
        $comments = Comment::where('theme_id', $topic->id)
            ->where('content', 'like', '%' . $q . '%')
            ->withAnswers()
            ->withThemeAndUser()
            ->onlyApproved()
            ->get();

        Log::info($comments);
        return [
            'count' => count($comments),
            'comments' => $comments
        ];
    }

    public function sort(Theme $topic, string $by): array {
        $comments = $by == 'popular'
            ? Comment::where('theme_id', $topic->id)
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->SortByAnswerCount()
                ->get()
            : Comment::where('theme_id', $topic->id)
                ->orderBy('created_at', 'asc')
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->get();

        return [
            'count' => count($comments),
            'comments' => $comments
        ];
    }

    public function createFromRequest(array $data, Theme $topic, User $user): Comment
    {
        $comment = Comment::create(array_merge($data, [
            'theme_id' => $topic->id,
            'user_id' => $user->id
        ]));

        Log::info($comment);

        return $comment;
    }

    public function updateFromRequest(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        Log::info($comment);

        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
