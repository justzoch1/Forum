<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ExceptionRespond
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isRedirection() || $response->isSuccessful() || $request->ajax()) {
            return $response;
        }

        $data = (array) json_decode(json_encode($response->getData()), true);
        $errorCode = $data['fault']['code'];
        Log::info($data);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($response->getData(), $errorCode);
        } else {
            return response()->view('errors', $data, $errorCode);
        }
    }
}
