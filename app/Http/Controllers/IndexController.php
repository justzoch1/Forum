<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Repositories\ThemeRepositories;
use App\Services\IndexControllerService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /*
     * Получить данные для страницы(популярные и последнии последнии темы)
     */
    public function index(ThemeRepositories $repository): array
    {
        $popularThemes = $repository->getPopularList();
        $latestThemes = $repository->getLatestList();

        return [
            'items' => [
                'latest' => $latestThemes,
                'popular' => $popularThemes,
            ]
        ];
    }

    /*
    * Искать по ключевым словами и фразам
    */
    public function search(Request $request, ThemeRepositories $repository, IndexControllerService $service): array
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
}

