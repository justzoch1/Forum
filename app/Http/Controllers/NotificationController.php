<?php

namespace App\Http\Controllers;

use App\Services\NotificationControllerService;
use App\Models\User;

class NotificationController extends Controller
{
    public function getList(NotificationControllerService $service) {
        $user = auth()->user();
        $notifications = $service->getUserNotifications($user);
        return ['items' => $notifications];
    }
}
