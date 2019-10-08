<?php

namespace App\Http\Resources\Rating;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelHistoryResource extends JsonResource
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
            'channel_id' => $this->channel_id,
            'followers' => $this->followers,
            'views' => $this->views,
            'rating' => $this->rating,
            'place' => $this->place,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'channel' => new ChannelResource($this->whenLoaded('channel'))
        ];
    }
}
