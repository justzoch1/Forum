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
            ->withAnswers()
            ->onlyApproved()
            ->withThemeAndUser()
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

    public function search(?string $q = ''): Collection
    {
        $query = Theme::query();

        if (!empty($q)) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        $themes = $query->withCount('comments')
            ->orderBy('comments_count', 'desc')->paginate(8);

        Log::info($themes);

        return $themes;
    }

    public function sort(Theme $topic, string $by): Collection {
        $comments = $by == 'popular'
            ? Comment::where('theme_id', $topic->id)
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->SortByAnswerCount()
                ->paginate(6)
                ->collect()
            : Comment::where('theme_id', $topic->id)
                ->onlyApproved()
                ->withAnswers()
                ->withThemeAndUser()
                ->latest('created_at')
                ->paginate(6)
                ->collect();

        return $comments;
    }
}
