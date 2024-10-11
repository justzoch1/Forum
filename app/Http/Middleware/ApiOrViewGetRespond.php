<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ViewNameService;

class ApiOrViewGetRespond
{
    private $viewNameService;

    public function __construct(ViewNameService $viewNameService)
    {
        $this->viewNameService = $viewNameService;
    }

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
            $viewName = $this->viewNameService->getViewNameFromController();
            return response()->view($viewName, (array) $response->getData());
        }
    }
}
