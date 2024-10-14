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
            ->withThemeAndUser()
            ->get();

        return [
            'count' => count($comments),
            'comments' => $comments,
        ];
    }

    public function search(Theme $topic, $q) {
        $comments = Comment::where('theme_id', $topic->id)
            ->where('content', 'like', '%' . $q . '%')
            ->withThemeAndUser()
            ->onlyApproved()
            ->get();

        return [
            'count' => count($comments),
            'comments' => $comments
        ];
    }

    public function sort(Theme $topic, $by) {
        $comments = $by == 'popular'
            ? Comment::where('theme_id', $topic->id)
                ->withCount('answers')
                ->withAnswers()
                ->withThemeAndUser()
                ->onlyApproved()
                ->orderBy('answers_count', 'desc')
                ->get()
            : Comment::where('theme_id', $topic->id)
                ->orderBy('created_at', 'asc')
                ->onlyApproved()
                ->withAnswers()
                ->get();

        return [
            'count' => count($comments),
            'comments' => $comments
        ];
    }

    public function create(array $data, Theme $topic): Comment
    {
        $theme = Theme::find($topic->id);

        if (!$theme) {
            throw new \Exception("Тема с таким а id $topic->id не найдена");
        }

        $data = array_merge($data, ['theme_id' => $topic->id]);
        $comment = Comment::create($data);
        return $comment;
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
