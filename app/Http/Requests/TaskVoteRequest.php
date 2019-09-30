<?php

namespace App\Http\Requests;

use App\Enums\VoteStatus;
use App\Rules\ValidCanVote;
use App\Rules\ValidTaskStatusForVote;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TaskVoteRequest extends FormRequest {

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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'vote' => [
                'required',
                new EnumValue(VoteStatus::class),
                new ValidTaskStatusForVote($request->route('task')),
                new ValidCanVote($request->route('task')),
            ]
        ];
    }


    public function messages()
    {
        return [
        ];
    }
}
