<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidCampaignUpdateStartDate implements Rule
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
        if(!$this->campaign->isStarted() && Carbon::parse($value)->lt(Carbon::now('UTC')))
        {
            $this->message = trans('api/campaign.from_cannot_be_less_than_now');
            return false;
        }

        if($this->campaign->isStarted() && Carbon::parse($value)->timestamp!=Carbon::parse($this->campaign->from)->timestamp)
        {
            $this->message = trans('api/campaign.not_allowed_change_from_when_it_started');
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