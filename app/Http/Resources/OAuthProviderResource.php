<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class OAuthProviderResource extends JsonResource
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
            'provider' => $this->provider,
            'provider_user_id' => $this->provider_user_id,
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'json' => $this->json,

            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
