<?php

namespace App\Rules;

use App\Models\AdvCampaign;
use Illuminate\Contracts\Validation\Rule;

class ValidCanCreateAdvTask implements Rule
{
    public $campaign_id;
    public $message;

    public function __construct($campaign_id)
    {
        $this->campaign_id = $campaign_id;
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

        $campaign = AdvCampaign::findOrFail($this->campaign_id);

        if(!$user->isAdvertiser() && !$user->isAdmin())
        {
            $this->message = trans('api/campaign.not_advertiser');
            return false;
        }

        if($campaign->user_id!=$user->id)
        {
            $this->message = trans('api/campaign.not_the_owner');
            return false;
        }

        if($campaign->isStarted())
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
        return trans('api/campaign.not_advertiser');
    }
}