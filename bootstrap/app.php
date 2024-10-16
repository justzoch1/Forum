<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Middleware\ExceptionRespond;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
            $middleware->append(ExceptionRespond::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
                if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                    return response()->json([
                        'fault' => [
                            'code' => 404,
                            'message' => 'Запрашиваемый ресурс не найден.'
                        ]
                    ], 404);
                } elseif ($e instanceof AuthorizationException) {
                    return response()->json([
                        'fault' => [
                            'code' => 401,
                            'message' => 'Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.',
                        ]
                    ], 401);
                } elseif($e instanceof AccessDeniedHttpException) {
                    return response()->json([
                        'fault' => [
                            'code' => 403,
                            'message' => 'У вас нет прав на этот ресурс',
                        ]
                    ], 403);
                } elseif ($e instanceof QueryException || $e instanceof HttpException) {
                    $message = $e->getMessage() ?: 'Неверный запрос.';
                    return response()->json([
                        'fault' => [
                            'code' => 400,
                            'message' => $message
                        ]
                    ], 400);
                } elseif ($e instanceof ValidationException || $e instanceof BadRequestHttpException) {
                    return response()->json([
                        'fault' => [
                            'code' => 422,
                            'message' => 'Введены неккоректные данные. Пожалуйста пересмотрите свой запрос и попробуйте снова.',
                            'errors' => $e->errors(),
                        ]
                    ], 422);
                } elseif ($e instanceof Exception) {
                    return response()->json([
                        'fault' => [
                            'code' => 500,
                            'message' => 'Произошла непредвиденная ошибка',
                            'errors' => $e->getMessage(),
                        ]
                    ], 500);
                } elseif ($e instanceof PDOException) {
                    return response()->json([
                        'fault' => [
                            'code' => 400,
                            'message' => 'Ошибка иньекции: ' . $e->getMessage(),
                        ]
                    ]);
                }
        });
    })->create();
