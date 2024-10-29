<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Theme;
use App\Models\User;
use App\Services\AnswerControllerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/answers/{comment}/{receiver}",
     *     summary="Добавить ответ на комментарий",
     *     description="Создает новый ответ на указанный комментарий и отправляет его указанному получателю.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Answers"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="ID комментария, на который добавляется ответ",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="receiver",
     *         in="path",
     *         required=true,
     *         description="ID пользователя, которому отправляется ответ",
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
     *                 description="Содержимое ответа"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное создание ответа",
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
     *                 @OA\Property(property="comment_id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="receiver_id", type="integer"),
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
     *             )
     *          )
     *         )
     *     )
     * )
     */
    public function create(Comment $comment, User $receiver, Request $request, AnswerControllerService $service): array
    {
        Gate::authorize('create', Answer::class);
        $answer = $service->createFromRequest($request->all(), $comment, $receiver, Auth::user());

        Cache::forget("comments_{$comment->theme_id}");
        return [
            'status' => 'success',
            'comment' => $answer,
        ];
    }

    /**
     * @OA\Put(
     *     path="/api/answers/{answer}",
     *     summary="Редактировать ответ",
     *     description="Обновляет содержимое указанного ответа.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Answers"},
     *     @OA\Parameter(
     *         name="answer",
     *         in="path",
     *         required=true,
     *         description="ID ответа, который нужно редактировать",
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
     *                 description="Новое содержимое ответа"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное обновление ответа",
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
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="comment_id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="receiver_id", type="integer"),
     *                 @OA\Property(
     *                     property="comment",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="content", type="string"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="last_updated", type="string", format="date-time"),
     *                     @OA\Property(property="theme_id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
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
     *             )
     *           )
     *         )
     *     )
     * )
     */
    public function update(Answer $answer, Request $request, AnswerControllerService $service): array
    {
        Gate::authorize('update', $answer);
        $answer = $service->updateFromRequest($answer, $request->all());

        Cache::forget("comments_{$answer->comment->theme_id}");
        return [
            'status' => 'success',
            'answer' => $answer
        ];
    }

    /**
     * @OA\Delete(
     *     path="/api/answers/{id}",
     *     summary="Удаление ответа",
     *     description="Удаляет комментарий по указанному ID.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Answers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID ответа, который нужно удалить",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="succes"
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
    public function delete(Answer $answer, AnswerControllerService $service): array
    {
        Gate::authorize('delete', $answer);
        $service->delete($answer);

        Cache::forget("comments_{$answer->comment->theme_id}");
        return [
            'status' => 'success',
        ];
    }
}
