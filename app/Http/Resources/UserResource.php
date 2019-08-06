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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'nickname' => $this->nickname,
            'email' => $this->when(Auth::user() && Auth::user()->id==$this->id, $this->email),
            'role_id' => $this->role_id,
            'avatar' => $this->avatar ? Storage::disk('public')->url(str_replace('public/storage', '', $this->avatar)) : '/img/default_avatar.jpg',
            'overlay' => $this->overlay ? Storage::disk('public')->url(str_replace('public/storage', '', $this->overlay)) : '/img/default_overlay.jpg',
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
