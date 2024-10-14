<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Models\Message;
use App\Models\User;
use App\Services\MessengerControllerService;

class MessengerController
{
    public function getListOfUsers(User $sender, User $receiver, MessengerControllerService $service): array
    {
        $message = $service->getListOfUser($sender, $receiver);

        return [
            'count' => count($message),
            'items' => $message
        ];
    }

    public function send(User $sender, User $receiver, MessageSendRequest $request, MessengerControllerService $service): array
    {

        $message = $service->createFromRequest($sender, $receiver, $request->validated());

        abort_unless($message, 404);

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }

    public function update(Message $message, MessageUpdateRequest $request, MessengerControllerService $service): array
    {
        $message = $service->updateFromRequest($message, $request->validated());
        abort_unless($message, 500);
        return [
            'status' => 'success',
            'message' => $message
        ];
    }

    public function delete(Message $message, MessengerControllerService $service): array
    {
        $service->delete($message);
        abort_unless($message, 500);
        return [
            'status' => 'success',
        ];
    }
}
