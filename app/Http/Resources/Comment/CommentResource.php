<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $fullname = @$this->user->first_name .' '. @$this->user->last_name;
        return [
            'id' => @$this->id,
            'body' => @$this->body,
            'user_name' => $fullname,
            'user_id' => @$this->user->id,
            'created_at' => @$this->created_at
        ];
    }
}
