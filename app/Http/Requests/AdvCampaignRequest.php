<?php

namespace App\Http\Requests;

use App\Rules\ValidCanCreateCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdvCampaignRequest extends FormRequest {
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
                        'from' => [
                            'required',
                            'date',
                            'after:now',
                            'before:to',
                        ],
                        'to' => [
                            'required',
                            'date',
                            'after:to'
                        ],
                        'title'  => [
                            'required',
                            'string',
                            'min:1',
                            new ValidCanCreateCampaign(),
                        ],
                        'brand'  => 'required|string|min:1',
                        'limit' => 'required|integer|min:0'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'from' => [
                            'sometimes',
                            'required',
                            'date',
                            'after:now',
                            'before:to',
                        ],
                        'to' => [
                            'sometimes',
                            'required',
                            'date',
                            'after:to'
                        ],
                        'title'  => [
                            'sometimes',
                            'required',
                            new ValidCanUpdateCampaign($request->route('campaign')),
                        ],
                        'brand'  => 'sometimes|required',
                        'limit' => 'sometimes|required|integer|min:0'
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
