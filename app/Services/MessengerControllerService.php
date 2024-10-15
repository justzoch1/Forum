<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MessengerControllerService
{
    public function getListOfUser(User $sender, User $receiver): array
    {
        $messages = Message::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->orderBy('created_at', 'desc')
            ->withSenderAndReceiver()
            ->get();

        return [
            'count' => count($messages),
            'messages' => $messages
        ];
    }

    public function createFromRequest(User $sender, User $receiver, $data): Message
    {
        $sender = User::find($sender->id);
        $receiver = User::find($receiver->id);

        Log::info(['Отправитель: ' => $sender, 'Получатель: ' => $receiver]);

        $message = Message::create(array_merge($data,
            [
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id
            ]));

        Log::info($message);

        return $message;
    }

    public function updateFromRequest(Message $message, $data): Message
    {
        $message->update($data);
        Log::info($message);
        return $message;
    }

    public function delete(Message $message): void
    {
        $message->delete();
    }
}
