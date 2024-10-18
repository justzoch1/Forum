<?php

namespace App\Http\Controllers;

use App\Services\NotificationControllerService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getList(NotificationControllerService $service) {
        $user = Auth::user();
        $notifications = $service->getUserNotifications($user);
        return ['items' => $notifications];
    }
}
