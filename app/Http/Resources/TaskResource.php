<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'stream_id' => $this->stream_id,
            'user_id' => $this->user_id,
            'small_desc' => $this->small_desc,
            'full_desc' => $this->full_desc,
            'is_superbowl' => $this->is_superbowl,
            'interval_time' => $this->interval_time,
            'min_donation' => $this->min_donation,
            'status' => TaskStatus::getInstance($this->status),
            'amount_donations' => $this->amount_donations,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'stream' => new StreamResource($this->whenLoaded('stream')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
