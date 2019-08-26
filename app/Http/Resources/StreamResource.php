<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamResource extends JsonResource
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
            'game_id' => $this->game_id,
            'title' => $this->title,
            'link' => $this->link,
            'start_at' => $this->start_at,
            'ended_at' => $this->ended_at,
            'status' => $this->status,
            'is_payed' => $this->is_payed,
            'allow_task_before_stream' => $this->allow_task_before_stream,
            'allow_task_when_stream' => $this->allow_task_when_stream,
            'min_amount_task_before_stream' => $this->min_amount_task_before_stream,
            'min_amount_task_when_stream' => $this->min_amount_task_when_stream,
            'min_amount_donate_task_before_stream' => $this->min_amount_donate_task_before_stream,
            'min_amount_donate_task_when_stream' => $this->min_amount_donate_task_when_stream,

            'quantity_donators' => $this->quantity_donators,
            'quantity_donations' => $this->quantity_donations,
            'amount_donations' => $this->amount_donations,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'user' => new UserResource($this->whenLoaded('user')),
            'game' => new GameResource($this->whenLoaded('game')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'tags' => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
