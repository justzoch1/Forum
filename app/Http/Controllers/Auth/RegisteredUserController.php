<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthControllerService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('blog.index', absolute: false));
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Получить токен после регистрации",
     *     tags={"Auth"},
     *     description="Возвращает информацию о пользователе после успешной регистрации.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ с данными пользователя",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="token", type="string", description="JWT токен доступа")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Неверный запрос",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=400),
     *                  @OA\Property(property="message", type="string", example="Неверный запрос.")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Нет прав для отправки сообщения",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=403),
     *                  @OA\Property(property="message", type="string", example="У вас нет прав на этот ресурс.")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Ресурс не найден",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="fault",
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example=404),
     *                  @OA\Property(property="message", type="string", example="Запрашиваемый ресурс не найден.")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Ошибка авторизации",
     *           @OA\JsonContent(
     *               @OA\Property(
     *                   property="fault",
     *                   type="object",
     *                   @OA\Property(property="code", type="integer", example=404),
     *                   @OA\Property(property="message", type="string", example="Вы не авторизированны. Пожалуйста пройдите авторизацию и возвращайтесь.")
     *               )
     *           )
     *       ),
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
    public function storeWithToken(RegisterRequest $request, AuthControllerService $service): array
    {
        $user = $service->register($request->validated());

        return [
            'user' => $user,
        ];
    }
}
