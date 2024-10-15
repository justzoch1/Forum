<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Models\Message;
use App\Models\User;
use App\Services\MessengerControllerService;

class MessengerController
{
    /*
    *  Получить список комментариев между мельзователями
    */
    public function getListOfUsers(User $sender, User $receiver, MessengerControllerService $service)
    {
        $message = $service->getListOfUser($sender, $receiver);
        return [
            'count' => count($message),
            'items' => $message
        ];
    }

    /*
    *  Отправить сообщение пользователю
    */
    public function send(User $sender, User $receiver, MessageSendRequest $request, MessengerControllerService $service): array
    {
        $message = $service->createFromRequest($sender, $receiver, $request->validated());
        return [
            'status' => 'success',
            'message' => $message,
        ];
    }

    /*
    *  Отредактировать сообщение
    */
    public function update(Message $message, MessageUpdateRequest $request, MessengerControllerService $service): array
    {
        $message = $service->updateFromRequest($message, $request->validated());
        return [
            'status' => 'success',
            'message' => $message
        ];
    }

    /*
     *  Удалить сообщение
     */
    public function delete(Message $message, MessengerControllerService $service): array
    {
        $service->delete($message);
        return [
            'status' => 'success',
        ];
    }
}
