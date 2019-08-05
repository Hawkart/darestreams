<?php

namespace App\Http\Resources;

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
            'logo' => $this->logo ? Storage::disk('public')->url($this->logo) : '/img/default_game.jpg',
            'logo_small' => $this->logo_small ? Storage::disk('public')->url($this->logo_small) : '/img/default_game_small.jpg',

            'streams' => StreamResource::collection($this->whenLoaded('streams')),
            'tags' => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
