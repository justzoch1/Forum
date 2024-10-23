<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
class NotificationControllerService
{
    public function getUserNotifications(User $user): array {
        $notifications = $user->notifications;
        Log::info($notifications);

        return [
            'count' => count($notifications),
            'notifications' => $notifications
        ];
    }
}
