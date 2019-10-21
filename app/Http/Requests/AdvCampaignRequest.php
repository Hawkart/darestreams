<?php

namespace App\Http\Requests;

use App\Rules\ValidCampaignLimit;
use App\Rules\ValidCampaignUpdateStartDate;
use App\Rules\ValidCanCreateCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\ValidCanUpdateCampaign;

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
                            'after:now',
                            'after:from'
                        ],
                        'title'  => [
                            'required',
                            'string',
                            'min:1',
                            new ValidCanCreateCampaign(),
                        ],
                        'brand'  => 'required|string|min:1',
                        'limit' => [
                            'required',
                            'integer',
                            'min:1',
                            new ValidCampaignLimit(),
                        ]
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    $campaign = $request->route('campaign');

                    return [
                        'from' => [
                            'sometimes',
                            'required',
                            'date',
                            //'after:now',
                            new ValidCampaignUpdateStartDate($campaign),
                            'before:to',
                        ],
                        'to' => [
                            'sometimes',
                            'required',
                            'date',
                            'after:now',
                            'after:from'
                        ],
                        'title'  => [
                            'required',
                            'string',
                            'min:1',
                            new ValidCanUpdateCampaign($campaign),
                        ],
                        'brand'  => 'sometimes|required|string|min:1',
                        'limit' => [
                            'sometimes',
                            'required',
                            'integer',
                            'min:1',
                            new ValidCampaignLimit(),
                        ]
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
