<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class CommentControllerService
{
    public function getListOfTopic(Theme $topic): array
    {
        $comments = Comment::where('theme_id', $topic->id)
            ->withAnswers()
            ->onlyApproved()
            ->withThemeAndUser()
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

        if (count($comments) < 1)
            return ['message' => 'Комментарии не были найдены'];

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

    public function createFromRequest(array $data, Theme $topic): Comment
    {
        $theme = Theme::find($topic->id);

        $comment = Comment::create(array_merge($data, [
            'theme_id' => $topic->id
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
