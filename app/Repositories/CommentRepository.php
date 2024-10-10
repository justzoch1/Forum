<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Theme;

class CommentRepository
{
    public function getList(): array
    {
        $comments = Comment::all();

        return [
            'count' => count($comments),
            'comments' => $comments,
        ];
    }

    public function getListOfTopic(Theme $topic): array
    {
        $comments = Comment::where('theme_id', $topic->id)->get();

        return [
            'count' => count($comments),
            'comments' => $comments,
        ];
    }
}
