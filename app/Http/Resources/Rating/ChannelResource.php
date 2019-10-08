<?php

namespace App\Http\Resources\Rating;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
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
            'name' => $this->name,
            'url' => $this->url,
            'dare' => $this->exist ? 'https://darestreams.com/channel/'.$this->name : '',
            'logo' => $this->json['logo'],
            'lang' => $this->lang,
            'game_id' => $this->game_id,
            'channel_id' => $this->channel_id,
            'followers' => $this->followers,
            'views' => $this->views,
            'rating' => $this->rating,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'game' => new \App\Http\Resources\Game($this->whenLoaded('game')),
            'channel' => new \App\Http\Resources\ChannelResource($this->whenLoaded('channel')),
            'history' => ChannelHistoryResource::collection($this->whenLoaded('history')),
            'lastHistory' => ChannelHistoryResource::collection($this->whenLoaded('lastHistory')),
        ];
    }
}
