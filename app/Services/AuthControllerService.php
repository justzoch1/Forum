<?php

namespace App\Services;

use App\Models\User;
use http\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthControllerService
{
    public function register(array $data): array
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $token = $user->createToken("API TOKEN")->plainTextToken;

        return array_merge($user->toArray(), ['token' => $token]);
    }

    public function token(array $data): string
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            // Обработка ошибки
        }
        $token = $user->createToken("API TOKEN")->plainTextToken;

        return $token;
    }
}
