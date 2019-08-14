<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Storage;

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
            'user_id' => $this->user_id,
            'title' => $this->title,
            'link' => $this->link,
            'game_id' => $this->game_id,
            'slug' => $this->slug,
            'description' => $this->description,
            'provider' => $this->provider,
            'views' => $this->views,
            'donates' => $this->donates,
            'logo' => $this->logo ? Storage::disk('public')->url(str_replace('public/storage', '', $this->logo)) : '/img/default_channel.jpg',
            'created_at' => $this->created_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'game' => new GameResource($this->whenLoaded('game')),
            'streams' => StreamResource::collection($this->whenLoaded('streams')),
            'tags' => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
