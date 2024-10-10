<?php

namespace App\Repositories;
use App\Repositories\Interfaces\TopicRepositoryInterface;
use App\Models\Theme;

class TopicRepository implements TopicRepositoryInterface
{
    public function getList(): array
    {
        $topics = Theme::all();

        return [
            'count' => count($topics),
            'topics' => $topics,
        ];
    }
}
