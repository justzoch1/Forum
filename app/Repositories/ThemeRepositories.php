<?php

namespace App\Repositories;

use App\Models\Theme;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ThemeRepositories
{
    public function getLatestList(): Collection
    {
        $topics = Theme::latest('id')
            ->limit(8)
            ->get();

        return $topics;
    }

    public function getPopularList(): LengthAwarePaginator
    {
        $topics = Theme::withCount('comments')->orderBy('comments_count', 'desc')->paginate(6);

        return $topics;
    }

    public function getOne(Theme $topic): Theme
    {
        $topic = Theme::withUser()->findOrFail($topic->id);

        $approvedCommentsCount = $topic->approvedCommentsCount();

        $topic->setAttribute('comments_count', $approvedCommentsCount);

        return $topic;
    }
}
