<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use App\Enums\VoteStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        $data = [
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
            'votes' => new VoteResource($this->whenLoaded('votes')),
            'stream' => new StreamResource($this->whenLoaded('stream')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];

        //Show results if already voted or voting finished
        if(in_array($this->status, [TaskStatus::VoteFinished, TaskStatus::PayFinished]))
        {
            $data['vote_yes'] = $this->vote_yes;
            $data['vote_no'] = $this->vote_no;
        }

        return $data;
    }
}
