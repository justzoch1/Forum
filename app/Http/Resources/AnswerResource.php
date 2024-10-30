<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment_id' => $this->comment->id,
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name
            ],
            'receiver' => [
                'id' => $this->receiver->id,
                'name' => $this->receiver->name
            ],
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
