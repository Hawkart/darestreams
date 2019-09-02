<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'amount' => $this->when(Auth::user() && Auth::user()->id==$this->user_id, $this->amount),

            'user' => new UserResource($this->whenLoaded('user')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')), //Todo: Make connecctions in Model
        ];
    }
}
