<?php

namespace App\Swagger\Controllers;

use App\Services\NotificationControllerService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Получает список уведомлений пользователя",
     *     tags={"Notifications"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными уведомлений",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="object",
     *                 @OA\Property(property="count", type="integer"),
     *                 @OA\Property(
     *                     property="notifications",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="notifiable_type", type="string"),
     *                         @OA\Property(property="notifiable_id", type="integer"),
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             @OA\Property(property="message", type="string"),
     *                             @OA\Property(property="sender_id", type="integer")
     *                         ),
     *                         @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
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
     *         response=401,
     *         description="Ошибка авторизации",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=401),
     *                 @OA\Property(property="message", type="string", example="Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.")
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
    public function getList(NotificationControllerService $service) {
        $user = Auth::user();
        $notifications = $service->getUserNotifications($user);
        return ['items' => $notifications];
    }
}
