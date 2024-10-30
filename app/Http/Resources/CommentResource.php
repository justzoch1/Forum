<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'theme_id' => $this->theme_id,
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'content' => $this->content,
            'answers' => AnswerResource::collection($this->answers),
            'created_at' => $this->created_at,
            'update_at' => $this->update_at
        ];
    }
}
