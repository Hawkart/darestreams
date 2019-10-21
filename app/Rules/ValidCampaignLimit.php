<?php

namespace App\Rules;

use App\Models\AdvCampaign;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidCampaignLimit implements Rule
{
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
        $amount = $user->account->amount;

        $limit = AdvCampaign::where('user_id', $user->id)
            ->where('to', '>', Carbon::now('UTC'))
            ->sum('limit');

        if($amount<$limit+$value)
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('api/campaign.not_enough_money');
    }
}