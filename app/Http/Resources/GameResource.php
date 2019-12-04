<?php

namespace App\Http\Resources;

use App\Http\Resources\Rating\GameHistoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class GameResource extends JsonResource
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
            'title' => $this->title,
            'title_short' => $this->title_short,
            'popularity' => $this->popularity,
            'logo' => getImageLink($this->logo, '/img/default_game.jpg'),
            'logo_small' => getImageLink($this->logo_small, '/img/default_game_small.jpg'),
            'views' => $this->views,

            'streams' => StreamResource::collection($this->whenLoaded('streams')),
            'channels' => ChannelResource::collection($this->whenLoaded('channels')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'history' => GameHistoryResource::collection($this->whenLoaded('history')),
            'lastHistory' => GameHistoryResource::collection($this->whenLoaded('lastHistory')),
        ];
    }
}
