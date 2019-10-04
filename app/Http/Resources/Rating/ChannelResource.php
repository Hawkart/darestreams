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
            'logo' => $this->json['logo'],
            'lang' => $this->lang,
            'exist' => $this->exist,
            'top' => $this->top,
            'followers' => $this->followers,
            'views' => $this->views,
            'rating' => $this->rating,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'history' => ChannelHistoryResource::collection($this->whenLoaded('history')),
        ];
    }
}
