<?php

namespace App\Http\Controllers;

use App\Services\TopicControllerService;

class TopicController extends Controller
{
    public function getList(TopicControllerService $repository): array
    {
        $topics = $repository->getList();

        return ['items' => $topics];
    }
}

