<?php

namespace App\Http\Resources;

use App\Enums\StreamStatus;
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
        $data = [
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'game_id' => $this->game_id,
            'title' => $this->title,
            'link' => $this->link,
            'start_at' => getW3cDatetime($this->start_at),
            'ended_at' => getW3cDatetime($this->ended_at),
            'status' => StreamStatus::getInstance($this->status),
            'allow_task_before_stream' => $this->allow_task_before_stream,
            'allow_task_when_stream' => $this->allow_task_when_stream,
            'min_amount_task_before_stream' => $this->min_amount_task_before_stream,
            'min_amount_task_when_stream' => $this->min_amount_task_when_stream,
            'min_amount_donate_task_before_stream' => $this->min_amount_donate_task_before_stream,
            'min_amount_donate_task_when_stream' => $this->min_amount_donate_task_when_stream,

            'quantity_donators' => $this->quantity_donators,
            'quantity_donations' => $this->quantity_donations,
            'amount_donations' => $this->amount_donations,
            'views' => $this->views,
            'preview' => getImageLink($this->preview, null),
            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),

            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'user' => new UserResource($this->whenLoaded('user')),
            'game' => new GameResource($this->whenLoaded('game')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'tags' => TagResource::collection($this->whenLoaded('tags'))
        ];

        if(isset($this->tasks_completed_count))
            $data['tasks_count'] = $this->tasks_completed_count;

        return $data;
    }
}
