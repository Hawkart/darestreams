<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AdvCampaignResource extends JsonResource
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
            'from' => getW3cDatetime($this->from),
            'to' => getW3cDatetime($this->to),
            'title' => $this->title,
            'brand' => $this->brand,
            'logo' => $this->logo,
            'limit' => $this->limit,
            'used_amount' => $this->used_amount,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'user' => new UserResource($this->whenLoaded('user')),
            'advTasks' => AdvTaskResource::collection($this->whenLoaded('advTasks'))
        ];
    }
}