<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidCanUpdateCampaign implements Rule
{
    public $campaign;
    public $message;

    /**
     * ValidCanUpdateCampaign constructor.
     * @param $campaign
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
        $this->message = '';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->user();
        $now = Carbon::now('UTC');

        if(!$user->isAdvertiser())
        {
            $this->message = trans('api/campaign.not_advertiser');
            return false;
        }

        if($this->campaign->user_id!=$user->id)
        {
            $this->message = trans('api/campaign.not_the_owner');
            return false;
        }

        if($this->campaign->isStarted())
        {
            $this->message = trans('api/campaign.already_started');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}