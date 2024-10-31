<?php

namespace App\Traits;

trait AdminPanel
{
    /**
     * Checking if the request is from the admin panel
     */
    protected function isAdminPanel(): bool {
        return request()->is('admin/*');
    }
}
