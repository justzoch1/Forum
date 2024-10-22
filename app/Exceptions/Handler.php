<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                    return response()->json([
                        'fault' => [
                            'code' => 404,
                            'message' => 'Запрашиваемый ресурс не найден.'
                        ]
                    ], 404);
                }
                elseif ($e instanceof AuthorizationException) {
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
                }
                elseif ($e instanceof QueryException || $e instanceof HttpException) {
                    $message = $e->getMessage() ?: 'Неверный запрос.';
                    return response()->json([
                        'fault' => [
                            'code' => 400,
                            'message' => $message
                        ]
                    ], 400);
                } else {
                if ($e instanceof NotFoundHttpException) {
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 404,
                            'message' => 'Запрашиваемый ресурс не найден.'
                        ]
                    ], 404);
                } elseif ($e instanceof AuthorizationException) {
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 401,
                            'message' => 'Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.',
                        ]
                    ], 401);
                } elseif($e instanceof AccessDeniedHttpException) {
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 403,
                            'message' => 'У вас нет прав на этот ресурс',
                        ]
                    ], 403);
                } elseif ($e instanceof QueryException || $e instanceof HttpException) {
                    $message = $e->getMessage() ?: 'Неверный запрос.';
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 400,
                            'message' => $message
                        ]
                    ], 400);
                } elseif ($e instanceof ValidationException) {
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 422,
                            'message' => 'Введены неккоректные данные. Пожалуйста пересмотрите свой запрос и попробуйте снова.',
                            'errors' => $e->errors(),
                        ]
                    ], 422);
                } elseif ($e instanceof Exception) {
                    return response()->view('errors', [
                        'fault' => [
                            'code' => 500,
                            'message' => 'Произошла непредвиденная ошибка',
                            'errors' => $e->getMessage(),
                        ]
                    ], 500);
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
                    ],500);
                }
                }
            }
        });
    }
}
