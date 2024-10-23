<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Models\Message;
use App\Models\User;
use App\Services\MessengerControllerService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendedMessage;

class MessengerController
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    /*
    *  Получить список комментариев между мельзователями
    */
    public function getListOfUsers(User $receiver, MessengerControllerService $service)
    {
        $sender = $this->user;
        $message = $service->getListOfUser($sender, $receiver);
        Log::info(['Отправитель - ' => $sender->id, 'Получатель' => $receiver->id]);
        return [
            'count' => count($message),
            'items' => $message
        ];
    }

    /*
    *  Отправить сообщение пользователю
    */
    public function send(User $receiver, MessageSendRequest $request, MessengerControllerService $service): array
    {
        $sender = $this->user;
        Gate::authorize('create', Message::class);

        $message = $service->createFromRequest($sender, $receiver, $request->validated());

        $receiver->notify(new SendedMessage($sender));

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
        Gate::authorize('update', $message);
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
        Gate::authorize('delete', $message);
        $service->delete($message);

        return [
            'status' => 'success',
        ];
    }
}
