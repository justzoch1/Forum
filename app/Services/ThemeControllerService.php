<?php


namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class ThemeControllerService
{
    public function getCommentsListOfTopic(Theme $topic, string $page)
    {
        $perPage = 6;
        $take = $page * $perPage;

        $comments = Comment::where('theme_id', $topic->id)
            ->onlyApproved()
            ->latest('created_at')
            ->take($take)
            ->get();

        // Log::info(['комментарии' => $comments]);

        return $comments;
    }

    public function getNextTopic(): Collection
    {
        $topics = Theme::withCount('comments')
            ->paginate(2)
            ->collect();

        return $topics;
    }

    public function sort(Theme $topic, string $by, string $page): Collection {
        $perPage = 6;
        $take = $page * $perPage;

        $comments = $by == 'popular'
            ? Comment::where('theme_id', $topic->id)
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->SortByAnswerCount()
                ->take($take)
                ->get()
            : Comment::where('theme_id', $topic->id)
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->latest('created_at')
                ->take($take)
                ->get();

        return $comments;
    }
}
