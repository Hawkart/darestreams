<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
                        'channel_id'  => 'required|exists:channels,id',
                        'title'  => 'required',
                        'link'     => 'required|url',
                        'start_at' => 'required|date|after:now',

                        'allow_task_before_stream' => 'required_without:allow_task_when_stream|boolean',    //accepted
                        'allow_task_when_stream' => 'required_without:allow_task_before_stream|boolean',    //accepted

                        'min_amount_task_before_stream' => 'required_if:allow_task_before_stream,1|regex:/^\d+(\.\d{1,2})?$/',
                        'min_amount_donate_task_before_stream' => 'required_if:allow_task_before_stream,1|regex:/^\d+(\.\d{1,2})?$/',

                        'min_amount_task_when_stream' => 'required_if:allow_task_when_stream,1|regex:/^\d+(\.\d{1,2})?$/',
                        'min_amount_donate_task_when_stream' => 'required_if:allow_task_when_stream,1|regex:/^\d+(\.\d{1,2})?$/',

                        //Todo: Add validation of superbowl
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
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
