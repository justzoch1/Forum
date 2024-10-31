<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class MessengerControllerService
{
    public function getListOfUser(User $sender, User $receiver): Collection
    {
        $messages = Message::where(function ($query) use ($sender) {
            $query->where('sender_id', $sender->id)
                ->orWhere('receiver_id', $sender->id);
        })
        ->where(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                ->orWhere('receiver_id', $receiver->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();
        Log::info(['сообщения' => $messages]);

        return $messages;
    }

    public function createFromRequest(User $sender, User $receiver, $data): Message
    {
        $sender = User::find($sender->id);
        $receiver = User::find($receiver->id);

        Log::info(['Отправитель: ' => $sender, 'Получатель: ' => $receiver]);

        $message = Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'content' => encrypt($data['content']),
            ]);

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
