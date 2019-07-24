<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thread_id' => $this->thread_id,
            'user_id' => $this->user_id,
            'last_read' => $this->last_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'thread' => new ThreadResource($this->whenLoaded('thread')),
        ];
    }
}
