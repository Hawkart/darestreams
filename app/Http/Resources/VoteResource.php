<?php

namespace App\Http\Resources;

use App\Enums\VoteStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
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
            'is_voted' => $this->vote == VoteStatus::Pending ? false : true
            /*'task_id' => $this->task_id,
            'vote' => $this->vote,
            'result' => $this->result,
            'amount_donations' => $this->amount_donations,

            'user' => new UserResource($this->whenLoaded('user')),
            'task' => new TaskResource($this->whenLoaded('task'))*/
        ];
    }
}
