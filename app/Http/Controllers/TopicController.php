<?php

namespace App\Http\Controllers;

use App\Services\TopicControllerService;

class TopicController extends Controller
{
    public function getList(TopicControllerService $repository): array
    {
        $topics = $repository->getList();

        if (!$topics) {
            abort(404, 'Ошибка: топики не были найдены');
        }

        return ['items' => $topics];
    }
}

