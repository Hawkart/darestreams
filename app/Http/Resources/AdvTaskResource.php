<?php

namespace App\Http\Resources;

use App\Enums\AdvTaskType;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvTaskResource extends JsonResource
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
            'campaign_id' => $this->campaign_id,
            'small_desc' => $this->small_desc,
            'full_desc' => $this->full_desc,
            'limit' => $this->limit,
            'price' => $this->price,
            'type' => AdvTaskType::getInstance($this->type),
            'min_rating' => $this->min_rating,
            'used_amount' => $this->used_amount,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'campaign' => new AdvCampaignResource($this->whenLoaded('campaign')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks'))
        ];
    }
}