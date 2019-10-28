<?php

namespace App\Rules;

use App\Models\Stream;
use App\Models\Rating\Channel as RatingChannel;
use App\Models\AdvTask;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskCanAdvCreate implements Rule
{
    public $stream_id;
    public $adv_task_id;
    public $message;

    /**
     * ValidCanTaskCreate constructor.
     * @param $stream_id
     */
    public function __construct($stream_id, $adv_task_id)
    {
        $this->stream_id = $stream_id;
        $this->adv_task_id = $adv_task_id;
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

        $stream = Stream::findOrFail($this->stream_id);
        $advTask = AdvTask::findOrFail($this->adv_task_id);
        $advCampaign = $advTask->campaign;

        if($stream->checkAlreadyFinished())
        {
            $this->message = trans('api/stream.stream_finished');
            return false;
        }

        //check fake rules
        if($stream->user->fake && !$user->fake)
        {
            $this->message = trans('api/task.failed_real_user_create_task_on_fake_stream');
            return false;
        }
        if(!$stream->user->fake && $user->fake)
        {
            $this->message = trans('api/task.failed_fake_user_create_task_on_real_stream');
            return false;
        }

        if($advCampaign->isFinished())
        {
            $this->message = trans('api/campaign.already_finished');
            return false;
        }

        if(!$user->ownerOfChannel($stream->channel_id))
        {
            $this->message = trans('api/task.failed_owner_of_channel_can_take_adv_task');
            return false;
        }

        $rating = RatingChannel::where('channel_id', $stream->channel_id)->first();
        if((!$rating && $advCampaign->min_rating!=0) || ($rating &&ceil($rating->rating/1000)<$advCampaign->min_rating))
        {
            $this->message = trans('api/task.low_rating');
            return false;
        }

        if($advTask->limit<$advTask->used_amount + $advTask->price)
        {
            $this->message = trans('api/task.adv_task_limit_exceeded');
            return false;
        }

        if($advCampaign->limit<$advCampaign->used_amount + $advTask->price)
        {
            $this->message = trans('api/task.adv_campaign_limit_exceeded');
            return false;
        }

        $advTasksDone = AdvTask::whereHas('tasks', function($q) use ($stream){
            $q->where('stream_id', $stream->id);
        })->get();

        if(count($advTasksDone)>0 && $advTasksDone[0]->campaign_id!=$advCampaign->id)
        {
            $this->message = trans('api/task.failed_take_different_adv_campaign');
            return false;
        }

        if(count($advTasksDone)>0 && in_array($advTask->id, $advTasksDone->pluck('id')))
        {
            $this->message = trans('api/task.failed_take_adv_task_second_time');
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