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
            'stream' => new StreamResource($this->whenLoaded('stream')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];

        //Get info by voting for auth user
        $canVote = false;
        $alreadyVote = false;

        if (!$this->whenLoaded('vote') instanceof \Illuminate\Http\Resources\MissingValue) {
            $userVote = VoteResource::collection($this->whenLoaded('vote'));
        } else {
            $userVote = [];
        }

        if(in_array($this->status, [TaskStatus::IntervalFinishedAllowVote, TaskStatus::AllowVote]) && count($userVote)>0 && isset($userVote[0]))
        {
            $canVote = true;

            if($userVote[0]->status!=VoteStatus::Pending)
                $alreadyVote = true;
        }

        //Show results if already voted or voting finished
        if($alreadyVote || in_array($this->status, [TaskStatus::VoteFinished, TaskStatus::PayFinished]) ||
            (Auth::user() && in_array($this->status, [TaskStatus::VoteFinished, TaskStatus::PayFinished, TaskStatus::AllowVote])
                && Auth::user()->id==$this->stream->user->id)
        )
        {
            $data['vote_yes'] = $this->vote_yes;
            $data['vote_no'] = $this->vote_no;
        }else{
            if (!$this->whenLoaded('vote') instanceof \Illuminate\Http\Resources\MissingValue)
            {
                $data['can_vote'] = $canVote;
                $data['vote'] = VoteResource::collection($this->whenLoaded('vote'));
            }
        }

        return $data;
    }
}
