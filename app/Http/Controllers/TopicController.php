<?php

namespace App\Http\Controllers;

use App\Repositories\TopicRepository;

class TopicController extends Controller
{
    public function getList(TopicRepository $repository): array
    {
        $topics = $repository->getList();

        if (!$topics) {
            abort(404, 'Ошибка: топики не были найдены');
        }

        return ['items' => $topics];
    }
}

