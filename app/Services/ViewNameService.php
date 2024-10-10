<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class ViewNameService
{
    public function getViewNameFromController()
    {
        $currentAction = Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);

        $viewName = str_replace('Controller', '', $controller);
        return strtolower($viewName);
    }
}
