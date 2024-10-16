<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;

class TopicControllerService
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
