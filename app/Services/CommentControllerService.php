<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;

class CommentControllerService
{
    public function getListOfTopic(Theme $topic): array
    {
        $comments = Comment::where('theme_id', $topic->id)
            ->withAnswers()
            ->get();

        return [
            'count' => count($comments),
            'comments' => $comments,
        ];
    }

    public function search(Theme $topic, $q) {
        $comments = Comment::where('theme_id', $topic->id)
            ->where('content', 'like', '%' . $q . '%')
            ->withAnswers()
            ->get();

        return [
            'count' => count($comments),
            'items' => $comments
        ];
    }

    public function sort(Theme $topic, $by) {
        $comments = $by == 'popular'
            ? Comment::where('theme_id', $topic->id)
                ->withCount('answers')
                ->withAnswers()
                ->orderBy('answers_count', 'desc')
                ->get()
            : Comment::where('theme_id', $topic->id)
                ->orderBy('created_at', 'desc')
                ->withAnswers()
                ->get();

        return [
            'count' => count($comments),
            'items' => $comments
        ];
    }

    public function create(array $data): Comment
    {
        $comment = Comment::create($data);
        return $comment;
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
    }
}
