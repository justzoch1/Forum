<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OauthService;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class YandexOauthController extends Controller
{
    protected bool $stateless = false;
    public function __construct(Request $request)
    {
        $this->stateless = $request->wantsJson() || $request->is('api/*') ? true : false;
    }

    public function redirect()
    {
        $driver = Socialite::driver('yandex');
        return $this->stateless ? $driver->stateless()->redirect() : $driver->redirect();
    }

    public function callback()
    {
        $yandexUser = $this->stateless ? Socialite::driver('yandex')->stateless()->user() : Socialite::driver('yandex')->user();

        $user = User::firstOrCreate(
            ['email' => $yandexUser->email],
            [
                'name' => $yandexUser->name,
                'email' => $yandexUser->email,
                'password' => Hash::make(Str::random(24)),
            ]
        );

        if ($this->stateless) {
            return route('login', [
                'email' => $user->email,
                'password' => $user->password,
            ]);
        } else {
            Auth::login($user);
            return redirect()->intended(route('blog.index', absolute: false));
        }
    }
}
