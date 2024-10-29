<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthControllerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/token",
     *     summary="Получить токен после аутентификации",
     *     tags={"Auth"},
     *     description="Возвращает токен доступа после успешной аутентификации.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", description="Email пользователя"),
     *             @OA\Property(property="password", type="string", description="Пароль пользователя")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с токеном",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="JWT токен доступа")
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
     *         description="Нет прав для отправки сообщения",
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
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="fault",
     *                 type="object",
     *                 @OA\Property(property="code", type="integer", example=422),
     *                 @OA\Property(property="message", type="string", example="Введены некорректные данные. Пожалуйста пересмотрите свой запрос и попробуйте снова."),
     *                 @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function token(\App\Http\Requests\LoginRequest $request, AuthControllerService $service): array
    {
        $token = $service->token($request->validated());

        return [
            'token' => $token,
        ];
    }
}
