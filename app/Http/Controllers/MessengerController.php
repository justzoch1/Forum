<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\MessengerControllerService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendedMessage;

class MessengerController
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    /**
     * @OA\Get(
     *     path="/api/messenger/{receiver}",
     *     summary="Получает список сообщений между текущим авторизированным пользователем и указанным пользователем",
     *     description="Возвращает список сообщений и информацию о пользователях",
     *     security={{"bearerAuth": {} }},
     *     tags={"Messages"},
     *     @OA\Parameter(
     *         name="receiver",
     *         in="path",
     *         required=true,
     *         description="ID получателя",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными сообщений и получателя",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="object",
     *                 @OA\Property(
     *                     property="count",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="messages",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(property="sender_id", type="integer"),
     *                         @OA\Property(property="receiver_id", type="integer"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="sender_name", type="string")
     *                     )
     *                 )
     *             ),
     *             @OA\Property( property="receiver", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Получатель не найден",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=404),
     *                 @OA\Property(property="message", type="string", example="Получатель не найден.")
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
     *                 @OA\Property(property="message", type="string", example="Произошла непредвиденная ошибка.")
     *             )
     *         )
     *     )
     * )
     */
    public function getListOfUsers(User $receiver, MessengerControllerService $service)
    {
        $sender = $this->user;
        $message = $service->getListOfUser($sender, $receiver);
        Log::info(['Отправитель - ' => $sender->id, 'Получатель' => $receiver->id]);

        return [
            'items' => [
                'messages' => MessageResource::collection($message),
            ],
            'receiver' => $receiver->name
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/messages/{receiver}",
     *     summary="Отправить сообщение пользователю",
     *     description="Отправляет сообщение указанному пользователю.",
     *          security={{"bearerAuth": {} }},
     *     tags={"Messages"},
     *     @OA\Parameter(
     *         name="receiver",
     *         in="path",
     *         required=true,
     *         description="ID получателя",
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
     *                 example="Hello"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная отправка сообщения",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="content", type="string", example="Hello"),
     *                 @OA\Property(property="sender_id", type="integer", example=1221),
     *                 @OA\Property(property="receiver_id", type="integer", example=1214),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T08:27:59.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T08:27:59.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=507)
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
    public function send(User $receiver, MessageSendRequest $request, MessengerControllerService $service): array
    {
        $sender = $this->user;
        Gate::authorize('create', Message::class);

        $message = $service->createFromRequest($sender, $receiver, $request->validated());

        $receiver->notify(new SendedMessage($sender));

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }

    /**
     * @OA\Put(
     *     path="/api/messages/{id}",
     *     summary="Отредактировать сообщение",
     *     description="Обновляет содержимое указанного сообщения.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Messages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID сообщения",
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
     *             ),
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное обновление сообщения",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="sender_id", type="integer"),
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
     *           )
     *         )
     *     )
     * )
     */
    public function update(Message $message, MessageUpdateRequest $request, MessengerControllerService $service): array
    {
        Gate::authorize('update', $message);
        $message = $service->updateFromRequest($message, $request->validated());

        return [
            'status' => 'success',
            'message' => $message
        ];
    }

    /**
     * @OA\Delete(
     *     path="/api/messages/{id}",
     *     summary="Удаляет сообщение",
     *     description="Удаляет сообщение по указанному ID.",
     *     security={{"bearerAuth": {} }},
     *     tags={"Messages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID сообщения, которое нужно удалить",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Сообщение успешно удалено"
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
     *             )
     *           )
     *         )
     *     )
     * )
     */
    public function delete(Message $message, MessengerControllerService $service): array
    {
        Gate::authorize('delete', $message);
        $service->delete($message);

        return [
            'status' => 'success',
        ];
    }
}
