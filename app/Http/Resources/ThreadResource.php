<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
            'subject' => $this->subject,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),
            'deleted_at' => getW3cDatetime($this->deleted_at),

            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'participants' => ParticipantResource::collection($this->whenLoaded('participants'))
        ];
    }
}
