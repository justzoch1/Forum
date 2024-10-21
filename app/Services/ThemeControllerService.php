<?php


namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;
use \Illuminate\Database\Eloquent\Collection;

class ThemeControllerService
{
    public function getCommentsListOfTopic(Theme $topic): Collection
    {
        $comments = Comment::where('theme_id', $topic->id)
            ->withAnswers()
            ->withThemeAndUser()
            ->onlyApproved()
            ->orderBy('created_at', 'desc')
            ->get();

        return $comments;
    }

    public function getNextTopic()
    {
        $topics = Theme::withCount('comments')->paginate(2);

        return $topics;
    }

    public function search(?string $q = '')
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
}
