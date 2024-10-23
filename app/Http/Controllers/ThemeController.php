<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Repositories\ThemeRepositories;
use App\Services\CommentControllerService;
use App\Services\IndexControllerService;
use App\Services\ThemeControllerService;
use Illuminate\Http\Request;

class ThemeController
{
    /*
     * Получить данные для страницы(конкретную тему, комментарии к ней, интересные темы и последнии темы)
     */
    public function index(Theme $topic, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $topic = $repository->getOne($topic);
        $comments = $service->getCommentsListOfTopic($topic);
        $latestTopics = $repository->getLatestList();
        $nextTopics = $service->getNextTopic();

        return [
            'items' => [
                'topic' => $topic,
                'latest' => $latestTopics,
                'next' => $nextTopics,
                'comments' => $comments,
            ]
        ];
    }

    /*
    * Искать по ключевым словами и фразам
    */
    public function search(Request $request, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $topics = $service->search($request->q);
        $latestThemes = $repository->getLatestList();

        return [
            'items' => [
                'latest' => $latestThemes,
                'popular' => $topics,
            ]
        ];
    }

    /*
    * Сортировать по популярности и дате загрузки
    */
    public function sort(Theme $topic, Request $request, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $comments = $service->sort($topic, $request->by);
        $topic = $repository->getOne($topic);
        $latestTopics = $repository->getLatestList();
        $nextTopics = $service->getNextTopic();

        return [
            'items' => [
                'topic' => $topic,
                'latest' => $latestTopics,
                'next' => $nextTopics,
                'comments' => $comments,
            ]
        ];
    }
}
