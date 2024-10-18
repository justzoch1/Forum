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
    public function token(\App\Http\Requests\LoginRequest $request, AuthControllerService $service): array
    {
        $token = $service->token($request->validated());

        return [
            'token' => $token,
        ];
    }
}
