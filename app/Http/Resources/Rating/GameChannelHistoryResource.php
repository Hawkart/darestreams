<?php

namespace App\Http\Resources\Rating;

use Illuminate\Http\Resources\Json\JsonResource;

class GameChannelHistoryResource extends JsonResource
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
            'game_history_id' => $this->game_id,
            'channel_id' => $this->game_id,
            'rating' => $this->rating,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'game_history' => new GameHistoryResource($this->whenLoaded('game_history')),
            'channel' => new ChannelResource($this->whenLoaded('channel'))
        ];
    }
}