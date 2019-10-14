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
            $this->message = 'Real user cannot create task for fake stream';
            return false;
        }
        if(!$stream->user->fake && $user->fake)
        {
            $this->message = 'Fake user cannot create task for real stream';
            return false;
        }

        if($advCampaign->isFinished())
        {
            $this->message = 'Advertisement campaign is already finished.';
            return false;
        }

        if(!$user->ownerOfChannel($stream->channel_id))
        {
            $this->message = 'Only owner of the channel can take advertisement task.';
            return false;
        }

        $rating = RatingChannel::where('channel_id', $stream->channel_id)->first();
        if(!$rating || ceil($rating->rating/1000)<$advCampaign->min_rating)
        {
            $this->message = 'Your rating less than minimum.';
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