<?php

namespace App\Swagger\Controllers;

use App\Models\Theme;
use App\Repositories\ThemeRepositories;
use App\Services\IndexControllerService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog",
     *     summary="Получает список тем",
     *     description="Возвращает список популярных и последних тем.",
     *     tags={"Blog"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными тем",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="object",
     *                 @OA\Property(
     *                     property="latest",
     *                     type="array",
     *                     @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="uuid", type="string"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="description", type="string"),
     *                          @OA\Property(property="created_at", type="string", format="date-time"),
     *                          @OA\Property(property="updated_at", type="string", format="date-time"),
     *                          @OA\Property(property="preview", type="string"),
     *                          @OA\Property(property="user_id", type="integer")
     *                      )
     *                 ),
     *                 @OA\Property(
     *                     property="popular",
     *                     type="array",
     *                     @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="uuid", type="string"),
     *                              @OA\Property(property="name", type="string"),
     *                              @OA\Property(property="description", type="string"),
     *                              @OA\Property(property="created_at", type="string"),
     *                              @OA\Property(property="updated_at", type="string"),
     *                              @OA\Property(property="preview", type="string"),
     *                              @OA\Property(property="user_id", type="integer"),
     *                              @OA\Property(property="comments_count", type="integer")
     *                          )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверный запрос",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property( property="code", type="integer", example=400),
     *                 @OA\Property( property="message", type="string", example="Неверный запрос.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Запрашиваемый ресурс не найден",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=404),
     *                 @OA\Property(property="message", type="string", example="Запрашиваемый ресурс не найден.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка: [сообщение об ошибке]."),
     *             )
     *         )
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/blog/search",
     *     summary="Получает список тем",
     *     description="Возвращает список популярных тем по ключевым фразам в описании и названии.",
     *     tags={"Blog"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Ключевая фраза для поиска тем",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными тем",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="object",
     *                 @OA\Property(
     *                     property="latest",
     *                     type="array",
     *                     @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="uuid", type="string"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="description", type="string"),
     *                          @OA\Property(property="created_at", type="string", format="date-time"),
     *                          @OA\Property(property="updated_at", type="string", format="date-time"),
     *                          @OA\Property(property="preview", type="string"),
     *                          @OA\Property(property="user_id", type="integer")
     *                      )
     *                 ),
     *                 @OA\Property(
     *                     property="popular",
     *                     type="array",
     *                     @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="uuid", type="string"),
     *                              @OA\Property(property="name", type="string"),
     *                              @OA\Property(property="description", type="string"),
     *                              @OA\Property(property="created_at", type="string", format="date-time"),
     *                              @OA\Property(property="updated_at", type="string", format="date-time"),
     *                              @OA\Property(property="preview", type="string"),
     *                              @OA\Property(property="user_id", type="integer"),
     *                              @OA\Property(property="comments_count", type="integer")
     *                          )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверный запрос",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property( property="code", type="integer", example=400),
     *                 @OA\Property( property="message", type="string", example="Неверный запрос.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Запрашиваемый ресурс не найден",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=404),
     *                 @OA\Property(property="message", type="string", example="Запрашиваемый ресурс не найден.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка: [сообщение об ошибке]."),
     *             )
     *         )
     *     )
     * )
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

