<?php

namespace App\Swagger\Controllers;

use App\Http\Requests\CommentSendRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Theme;
use App\Services\CommentControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /*
    *  Отфильтровать комментариии по темам
    */
    public function getListOfTopic(Theme $topic, CommentControllerService $service): array
    {
        $comments = $service->getListOfTopic($topic);
        return [
            'status' => 'success',
            'items' => $comments,
            'topic' => $topic
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/comments/{topic}",
     *     summary="Добавить комментарий к теме",
     *     description="Создает новый комментарий для указанной темы.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="topic",
     *         in="path",
     *         required=true,
     *         description="ID темы, к которой добавляется комментарий",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="content",
     *                 type="string",
     *                 description="Содержимое комментария"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное создание комментария",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="comment",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="theme_id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
     *         response=403,
     *         description="Нет прав на ресурс",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=403),
     *                 @OA\Property(property="message", type="string", example="У вас нет прав на этот ресурс.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ресурс не найден",
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
     *          response=401,
     *          description="Ошибка авторизации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=404),
     *                  @OA\Property(property="message", type="string", example="Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=422),
     *                  @OA\Property(property="message", type="string", example="Введены некорректные данные. Пожалуйста пересмотрите свой запрос и попробуйте снова."),
     *                  @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *              )
     *           )
     *         ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка."),
     *                 @OA\Property( property="errors", type="array", @OA\Items(type="string")
     *               )
     *             )
     *         )
     *     )
     * )
     */
    public function left(Theme $topic, CommentSendRequest $request, CommentControllerService $service): array
    {
        Gate::authorize('create', Comment::class);
        $comment = $service->createFromRequest($request->validated(), $topic, Auth::user());

        Cache::forget("comments_{$topic->id}");
        return [
            'status' => 'success',
            'comment' => $comment,
        ];
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     summary="Редактировать комментарий",
     *     description="Обновляет комментарий по указанному ID.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария, который нужно редактировать",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="content",
     *                 type="string",
     *                 example="Новый текст комментария"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="pending"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комментарий успешно обновлен",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="comment",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="last_updated", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="theme_id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
     *         response=403,
     *         description="Нет прав на ресурс",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=403),
     *                 @OA\Property(property="message", type="string", example="У вас нет прав на этот ресурс.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ресурс не найден",
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
     *          response=401,
     *          description="Ошибка авторизации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=404),
     *                  @OA\Property(property="message", type="string", example="Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=422),
     *                  @OA\Property(property="message", type="string", example="Введены некорректные данные. Пожалуйста пересмотрите свой запрос и попробуйте снова."),
     *                  @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *              )
     *           )
     *         ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка."),
     *                 @OA\Property( property="errors", type="array", @OA\Items(type="string")
     *               )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Comment $comment, CommentUpdateRequest $request, CommentControllerService $service): array
    {
        Gate::authorize('update', $comment);
        $comment = $service->updateFromRequest($comment, $request->validated());

        Cache::forget("comments_{$comment->theme_id}");
        return [
            'status' => 'success',
            'comment' => $comment
        ];
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     summary="Удаление комментария",
     *     description="Удаляет комментарий по указанному ID.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария, который нужно удалить",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Комментарий успешно удален"
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
     *         response=403,
     *         description="Нет прав на ресурс",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=403),
     *                 @OA\Property(property="message", type="string", example="У вас нет прав на этот ресурс.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ресурс не найден",
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
     *          response=401,
     *          description="Ошибка авторизации",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=404),
     *                  @OA\Property(property="message", type="string", example="Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка."),
     *                 @OA\Property( property="errors", type="array", @OA\Items(type="string")
     *               )
     *             )
     *         )
     *     )
     * )
     */
    public function delete(Comment $comment, CommentControllerService $service): array
    {
        Gate::authorize('delete', $comment);
        $service->delete($comment);

        Cache::forget("comments_{$comment->theme_id}");
        Log::info("Comment deleted ");
        return [
            'status' => 'success',
        ];
    }
}
