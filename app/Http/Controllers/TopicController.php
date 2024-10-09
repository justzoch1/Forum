<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\TopicRepository;

class TopicController extends Controller
{
    public function getList(TopicRepository $repository)
    {
        $topics = $repository->getList();

        if (!$topics) {
            abort(404, 'Ошибка: топики не были найдены');
        }

        return ['topics' => $topics];
    }
}

