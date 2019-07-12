<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'currency' => $this->currency,
            'amount' => $this->amount,

            'user' => new UserResource($this->whenLoaded('user')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')), //Todo: Make connecctions in Model
        ];
    }
}
