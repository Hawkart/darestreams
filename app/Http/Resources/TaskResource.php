<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use App\Enums\VoteStatus;
use Carbon\Carbon;
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
        $finish_at = (intval($this->interval_time)>0 && !empty($this->start_active)) ?
                        getW3cDatetime(Carbon::parse($this->start_active)->addMinutes($this->interval_time)) : null;

        $completed_time = (!empty($this->start_active)) ? $this->updated_at->diffInMinutes($this->start_active) : null;

        $data = [
            'id' => $this->id,
            'stream_id' => $this->stream_id,
            'user_id' => $this->user_id,
            'small_desc' => $this->small_desc,
            'full_desc' => $this->full_desc,
            'is_superbowl' => $this->is_superbowl,
            'interval_time' => $this->interval_time,
            'min_donation' => $this->min_donation,
            'status' => empty($this->status) ? TaskStatus::getInstance(TaskStatus::Created) : TaskStatus::getInstance($this->status),
            'amount_donations' => $this->amount_donations,
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),
            'start_active' => getW3cDatetime($this->start_active),
            'finish_at' => $finish_at,  //Todo: !!
            'completed_time' => $completed_time,
            'comment' => $this->comment,

            'user' => new UserResource($this->whenLoaded('user')),
            'votes' => VoteResource::collection($this->whenLoaded('votes')),
            'stream' => new StreamResource($this->whenLoaded('stream')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'advTask' => new AdvTaskResource($this->whenLoaded('advTask')),
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
