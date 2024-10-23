<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiOrViewPostRespond
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($request->wantsJson() || $request->is('api/*')) {
            // Запрос для Api
            return response()->json($response->getData());
        } else {
            // Запрос для Web
            return redirect()->back()->with('success');
        }
    }
}
