<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;

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
            'money' => $this->money,
            'status' => TransactionStatus::getInstance($this->status),
            'type' => TransactionType::getInstance($this->type),
            'created_at' => getW3cDatetime($this->created_at),
            'updated_at' => getW3cDatetime($this->updated_at),

            'currency' => $this->when($this->type!=TransactionType::Donation, $this->currency),
            'payment' => $this->when($this->type!=TransactionType::Donation, $this->payment),
            'exid' => $this->when($this->type!=TransactionType::Donation, $this->exid),

            'task' => new TaskResource($this->whenLoaded('task')),
            'account_sender' => new AccountResource($this->whenLoaded('accountSender')),
            'account_receiver' => new AccountResource($this->whenLoaded('accountReceiver'))
        ];
    }
}
