<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Storage;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'full_name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->when(Auth::user() && Auth::user()->id==$this->id, $this->email),
            'role_id' => $this->role_id,
            'avatar' => getImageLink($this->avatar, '/img/default_avatar.jpg'),
            'overlay' => getImageLink($this->overlay, '/img/default_overlay.jpg'),
            'donates' => $this->when(isset($this->donates), $this->donates),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'account' => new AccountResource($this->whenLoaded('account')),
            'oauthProviders' => $this->when(Auth::user() && Auth::user()->id==$this->id, function (){
                return OAuthProviderResource::collection($this->whenLoaded('oauthProviders'));
            }),
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'streams' => StreamResource::collection($this->whenLoaded('streams')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
