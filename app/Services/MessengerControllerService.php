<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;

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

        if (!$sender || !$receiver) {
            throw new \Exception("История сообщений между этими пользователями не найдена");
        }

        $message = Message::create(array_merge($data,
            [
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id
            ]));

        return $message;
    }

    public function updateFromRequest(Message $message, $data): Message
    {
        $message->update($data);
        return $message;
    }

    public function delete(Message $message): void
    {
        $message->delete();
    }
}
