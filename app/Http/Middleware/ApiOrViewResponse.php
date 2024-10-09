<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiOrViewResponse
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
            // Запрос для API
            return response()->json($response->getData());
        } else {
            // Запрос для веб-интерфейса
            $viewName = $this->getViewNameFromController();
            return response()->view($viewName, (array) $response->getData());
        }
    }

    private function getViewNameFromController()
    {
        $currentAction = Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);

        $viewName = str_replace('Controller', '', $controller);
        return strtolower($viewName);
    }
}
