<?php

namespace App\Repositories;

use App\Models\Theme;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class ThemeRepositories
{
    public function getLatestList(): LengthAwarePaginator
    {
        $topics = Theme::orderBy('created_at', 'desc')->paginate(6);

        return $topics;
    }

    public function getPopularList(): LengthAwarePaginator
    {
        $topics = Theme::withCount('comments')->orderBy('comments_count', 'desc')->paginate(6);

        return $topics;
    }

    public function getOne(Theme $topic): Theme
    {
        $topic = Theme::findOrFail($topic->id);
        Log::info($topic);
        return $topic;
    }
}
