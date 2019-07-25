<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TaskRequest extends FormRequest {
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
                        'description' => 'required|string|min:1',
                        'interval_time' => [
                            Rule::requiredIf($request->get('interval_until_end')),
                            'numeric|min:1'
                        ],
                        'min_amount' => "required|regex:/^\d+(\.\d{1,2})?$/",
                        'min_amount_superbowl' => [
                            Rule::requiredIf($request->get('is_superbowl')),
                            "regex:/^\d+(\.\d{1,2})?$/"
                        ],
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'check_vote' => 'boolean',
                        'status' => ""
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
