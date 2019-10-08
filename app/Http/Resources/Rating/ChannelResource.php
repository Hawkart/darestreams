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
            'exist' => $this->exist,
            'followers' => $this->followers,
            'views' => $this->views,
            'rating' => $this->rating,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'history' => ChannelHistoryResource::collection($this->whenLoaded('history')),
            'h' => ChannelHistoryResource::collection($this->whenLoaded('h')),
        ];
    }
}
