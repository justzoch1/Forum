<?php

namespace App\Http\Controllers;

use App\Services\NotificationControllerService;
use App\Models\User;

class NotificationController extends Controller
{
    public function getList(User $user, NotificationControllerService $service) {
        $notifications = $service->getUserNotifications($user);
        return ['items' => $notifications];
    }
}
