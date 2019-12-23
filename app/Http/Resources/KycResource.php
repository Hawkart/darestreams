<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KycResource extends JsonResource
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

            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'full_name' => $this->full_name,
            'sex' => $this->sex,

            'date_birth' => $this->date_birth,
            'city_birth' => $this->city_birth,
            'state_birth' => $this->state_birth,
            'country_birth' => $this->country_birth,

            'country_tax' => $this->country_tax,
            'inn_tax' => $this->inn_tax,
            'us_social_number_tax' => $this->us_social_number_tax,
            'us_taxpayer_number_tax' => $this->us_taxpayer_number_tax,

            'home_street_1' => $this->home_street_1,
            'home_street_2' => $this->home_street_2,
            'home_city' => $this->home_city,
            'home_state' => $this->home_state,
            'home_zip_code' => $this->home_zip_code,
            'home_country' => $this->home_country,

            'mailing_is_home' => $this->mailing_is_home,
            'mailing_street_1' => $this->mailing_street_1,
            'mailing_street_2' => $this->mailing_street_2,
            'mailing_city' => $this->mailing_city,
            'mailing_state' => $this->mailing_state,
            'mailing_zip_code' => $this->mailing_zip_code,
            'mailing_country' => $this->home_country,

            'phone' => $this->phone,
            'personal_verified' => $this->personal_verified,
            'passport_verified' => $this->passport_verified,

            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}