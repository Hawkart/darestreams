<?php

namespace App\Rules;

use App\Models\AdvCampaign;
use Illuminate\Contracts\Validation\Rule;

class ValidCanCreateAdvTask implements Rule
{
    public $campaign;
    public $message;

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

        if(!$user->isAdvertiser() && !$user->isAdmin())
        {
            $this->message = trans('api/campaign.not_advertiser');
            return false;
        }

        if($this->campaign ->user_id!=$user->id && !$user->isAdmin())
        {
            $this->message = trans('api/campaign.not_the_owner');
            return false;
        }

        if($this->campaign->isFinished())
        {
            $this->message = trans('api/campaign.already_finished');
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