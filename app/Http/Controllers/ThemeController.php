<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\LatestTopicResource;
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
    /**
     * @OA\Get(
     *     path="/api/blog/{id}",
     *     summary="Получить данные для страницы темы",
     *     tags={"Theme"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID темы",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Номер страницы комментариев",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными темы",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="object",
     *                 @OA\Property(
     *                     property="topic",
     *                     type="object",
     *                     description="Данные темы",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="uuid", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="preview", type="string"),
     *                     @OA\Property(property="comments_count", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 ),
     *                 @OA\Property(
     *                     property="latest_topics",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="next_topics",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="preview", type="string"),
     *                         @OA\Property(property="user_id", type="integer"),
     *                         @OA\Property(property="comments_count", type="integer"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="comments",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", description="ID комментария"),
     *                         @OA\Property(property="theme_id", type="integer"),
     *                         @OA\Property(
     *                             property="author",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="name", type="string")
     *                         ),
     *                         @OA\Property(
     *                             property="answers",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="comment_id", type="integer"),
     *                                 @OA\Property(
     *                                     property="author",
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer"),
     *                                     @OA\Property(property="name", type="string")
     *                                 ),
     *                                 @OA\Property(
     *                                     property="receiver",
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer"),
     *                                     @OA\Property(property="name", type="string")
     *                                 ),
     *                                 @OA\Property(property="content", type="string"),
     *                                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time")
     *                             )
     *                         ),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string"),
     *                     )
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
     *                 @OA\Property(property="code", type="integer", example=400),
     *                 @OA\Property(property="message", type="string", example="Неверный запрос.")
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
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка."),
     *                 @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request, Theme $topic, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $page = $request->page != null ? $request->page : 1;

        $topic = Cache::remember("topic_{$topic->id}", 3600, function () use ($repository, $topic) {
            return $repository->getOne($topic);
        });

        $comments = $service->getCommentsListOfTopic($topic, $page);

        $latestTopics = Cache::remember('latest_topics', 3600, function () use ($repository) {
            return $repository->getLatestList();
        });

        $nextTopics = Cache::remember('popular_topics', 3600, function () use ($service, $topic) {
            return $service->getNextTopic();
        });

        return [
            'items' => [
                'topic' => $topic,
                'latest_topics' => LatestTopicResource::collection($latestTopics),
                'next_topics' => $nextTopics,
                'comments' => CommentResource::collection($comments),
            ]
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/blog/{id}/sort",
     *     summary="Сортировать темы по популярности и дате загрузки",
     *     tags={"Theme"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID темы для сортировки",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="by",
     *          in="query",
     *          required=true,
     *          description="Параметр по которому будет идти сортировка(popular или что-либо иное)",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=true,
     *          description="Страница комментариев которые будут остортированы",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ с данными темы",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="items",
     *                  type="object",
     *                 @OA\Property(
     *                      property="topic",
     *                      type="object",
     *                      description="Данные темы",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="uuid", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="preview", type="string"),
     *                      @OA\Property(property="comments_count", type="integer"),
     *                      @OA\Property(property="created_at", type="string", format="date-time"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time"),
     *                  ),
     *                  @OA\Property(
     *                      property="latest_topics",
     *                      type="array",
     *                     @OA\Items(
     *                         type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="name", type="string"),
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="next_topics",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="preview", type="string"),
     *                          @OA\Property(property="user_id", type="integer"),
     *                          @OA\Property(property="comments_count", type="integer"),
     *                          @OA\Property(property="description", type="string"),
     *                          @OA\Property(property="created_at", type="string", format="date-time"),
     *                          @OA\Property(property="updated_at", type="string", format="date-time"),
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="comments",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer", description="ID комментария"),
     *                         @OA\Property(property="theme_id", type="integer"),
     *                          @OA\Property(
     *                              property="author",
     *                              type="object",
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="name", type="string")
     *                          ),
     *                          @OA\Property(
     *                              property="answers",
     *                              type="array",
     *                              @OA\Items(
     *                                  type="object",
     *                                  @OA\Property(property="id", type="integer"),
     *                                  @OA\Property(property="comment_id", type="integer"),
     *                                  @OA\Property(
     *                                      property="author",
     *                                      type="object",
     *                                      @OA\Property(property="id", type="integer"),
     *                                      @OA\Property(property="name", type="string")
     *                                  ),
     *                                 @OA\Property(
     *                                     property="receiver",
     *                                      type="object",
     *                                      @OA\Property(property="id", type="integer"),
     *                                      @OA\Property(property="name", type="string")
     *                                  ),
     *                                  @OA\Property(property="content", type="string"),
     *                                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                                  @OA\Property(property="updated_at", type="string", format="date-time")
     *                              )
     *                          ),
     *                          @OA\Property(property="content", type="string"),
     *                          @OA\Property(property="created_at", type="string", format="date-time"),
     *                          @OA\Property(property="updated_at", type="string"),
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверный запрос",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=400),
     *                 @OA\Property(property="message", type="string", example="Неверный запрос.")
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
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка."),
     *                 @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function sort(Request $request, Theme $topic, ThemeRepositories $repository, ThemeControllerService $service): array
    {
        $page = $request->page != null ? $request->page : 1;

        $topic = Cache::remember("topic_{$topic->id}", 3600, function () use ($repository, $topic) {
            return $repository->getOne($topic);
        });

        $comments = $service->sort($topic, $request->by, $page);

        $latestTopics = Cache::remember('latest_topics', 3600, function () use ($repository) {
            return $repository->getLatestList();
        });

        $nextTopics = Cache::remember('popular_topics', 3600, function () use ($service, $topic) {
            return $service->getNextTopic();
        });

        return [
            'items' => [
                'topic' => $topic,
                'latest_topics' => LatestTopicResource::collection($latestTopics),
                'next_topics' => $nextTopics,
                'comments' => CommentResource::collection($comments),
            ]
        ];
    }
}
