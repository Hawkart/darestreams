<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Storage;

class TransactionResource extends JsonResource
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
            'task_id' => $this->task_id,
            'account_sender_id' => $this->account_sender_id,
            'account_receiver_id' => $this->account_receiver_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'task' => new TaskResource($this->whenLoaded('task')),
            'accountSender' => new AccountResource($this->whenLoaded('accountSender')),
            'accountReceiver' => new AccountResource($this->whenLoaded('accountReceiver'))
        ];
    }
}
