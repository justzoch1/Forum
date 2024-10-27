<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Repositories\ThemeRepositories;
use App\Services\CommentControllerService;
use App\Services\IndexControllerService;
use App\Services\ThemeControllerService;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThemeController
{
    /*
     * Получить данные для страницы(конкретную тему, комментарии к ней, интересные темы и последнии темы)
     */
    public function index(Theme $topic, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $topic = Cache::remember("topic_{$topic->id}", 3600, function () use ($repository, $topic) {
            return $repository->getOne($topic);
        });

        $comments = Cache::remember("comments_{$topic->id}", 3600, function () use ($service, $topic) {
            return $service->getCommentsListOfTopic($topic);
        });

        $latestTopics = Cache::remember('latest_topics', 3600, function () use ($repository) {
            return $repository->getLatestList();
        });

        $nextTopics = Cache::remember('popular_topics', 3600, function () use ($service, $topic) {
            return $service->getNextTopic();
        });

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
     * Получить следующие комментарии
     */
    public function getMoreComments(Request $request, Theme $topic, ThemeControllerService $service): JsonResponse
    {
        $comments = $service->getCommentsListOfTopic($topic);

        return response()->json(['more_comments' => $comments]);
    }

    /*
    * Искать по ключевым словами и фразам
    */
    public function search(Request $request, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $topics = $service->search($request->q);
        $latestTopics = $repository->getLatestList();

        return [
            'items' => [
                'latest' => $latestTopics,
                'popular' => $topics,
            ]
        ];
    }

    /*
    * Сортировать по популярности и дате загрузки
    */
    public function sort(Theme $topic, Request $request, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $topic = Cache::remember("topic_{$topic->id}", 3600, function () use ($repository, $topic) {
            return $repository->getOne($topic);
        });

        $comments = Cache::remember("comments_{$topic->id}", 3600, function () use ($service, $topic, $request) {
            return $service->sort($topic, $request->by);
        });

        $latestTopics = Cache::remember('latest_topics', 3600, function () use ($repository) {
            return $repository->getLatestList();
        });

        $nextTopics = Cache::remember('popular_topics', 3600, function () use ($service, $topic) {
            return $service->getNextTopic();
        });

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
