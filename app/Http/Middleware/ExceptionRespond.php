<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionRespond
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isRedirection() || $response->isSuccessful() || $request->ajax()) {
            return $next($request);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($response->getData());
        } else {
            return response()->view('errors', (array) json_decode(json_encode($response->getData()), true));
        }
    }
}
