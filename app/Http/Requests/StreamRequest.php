<?php

namespace App\Http\Requests;

use App\Rules\AllowsChangeStreamStatus;
use App\Rules\ValidChannelDontHaveActiveStreams;
use App\Rules\ValidChannelOfAuthUser;
use App\Rules\ValidTaskCreateBeforeOrWhileStreamStart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Enums\StreamStatus;
use BenSampo\Enum\Rules\EnumValue;

class StreamRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'channel_id'  => [
                            'required',
                            'exists:channels,id',
                            new ValidChannelOfAuthUser(),
                            new ValidChannelDontHaveActiveStreams()
                        ],
                        'game_id'  => 'required|exists:games,id',
                        'title'  => 'required',
                        'link'     => 'required|url',
                        'start_at' => 'required|date|after:now',
                        'allow_task_before_stream' => [
                            'required_unless:allow_task_when_stream,1',
                            new ValidTaskCreateBeforeOrWhileStreamStart($this->all())
                        ],
                        'allow_task_when_stream'  => [
                            'required_unless:allow_task_before_stream,1',
                            new ValidTaskCreateBeforeOrWhileStreamStart($this->all())
                        ],
                        'min_amount_task_before_stream' => 'required_if:allow_task_before_stream,1|integer|min:0',
                        'min_amount_donate_task_before_stream' => 'required_if:allow_task_before_stream,1|integer|min:0',
                        'min_amount_task_when_stream' => 'required_if:allow_task_when_stream,1|integer|min:0',
                        'min_amount_donate_task_when_stream' => 'required_if:allow_task_when_stream,1|integer|min:0'

                        //Todo: Add validation of superbowl
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'game_id'  => 'sometimes|exists:games,id',
                        'title'  => 'sometimes|required',
                        'link'     => 'sometimes|required|url',
                        'start_at' => 'sometimes|required|date|after:now',
                        'status' => [
                            'sometimes',
                            'required',
                            new EnumValue(StreamStatus::class),
                            new AllowsChangeStreamStatus($request->route('stream'))
                        ],
                        'allow_task_before_stream' => [
                            'sometimes',
                            'required_unless:allow_task_when_stream,1',
                            new ValidTaskCreateBeforeOrWhileStreamStart($this->all())
                        ],
                        'allow_task_when_stream'  => [
                            'sometimes',
                            'required_unless:allow_task_before_stream,1',
                            new ValidTaskCreateBeforeOrWhileStreamStart($this->all())
                        ],
                        'min_amount_task_before_stream' => 'sometimes|required_if:allow_task_before_stream,1|integer|min:0',
                        'min_amount_donate_task_before_stream' => 'sometimes|required_if:allow_task_before_stream,1|integer|min:0',
                        'min_amount_task_when_stream' => 'sometimes|required_if:allow_task_when_stream,1|integer|min:0',
                        'min_amount_donate_task_when_stream' => 'sometimes|required_if:allow_task_when_stream,1|integer|min:0',
                    ];
                }
            default:break;
        }
    }


    public function messages()
    {
        return [
        ];
    }
}
