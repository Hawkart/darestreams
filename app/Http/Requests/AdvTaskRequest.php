<?php

namespace App\Http\Requests;

use App\Enums\AdvTaskType;
use App\Rules\ValidCanCreateAdvTask;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdvTaskRequest extends FormRequest {
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
                        'small_desc' => 'required|string|min:1',
                        'full_desc' => 'required|string|min:1',
                        'limit' => 'required|integer|min:0',
                        'price' => [
                            'required',
                            'numeric',
                            'min:0',
                            new ValidCanCreateAdvTask($request->route('campaign'))
                        ],
                        'type' => [
                            'required',
                            'integer',
                            new EnumValue(AdvTaskType::class)
                        ],
                        'min_rating' => 'required|integer|min:0',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'small_desc' => 'sometimes|required|string|min:1',
                        'full_desc' => 'sometimes|required|string|min:1',
                        'limit' => 'sometimes|required|integer|min:0',
                        'price' => [
                            'required',
                            'numeric',
                            'min:0',
                            new ValidCanCreateAdvTask($request->route('campaign'))
                        ],
                        'type' => [
                            'sometimes',
                            'required',
                            'integer',
                            new EnumValue(AdvTaskType::class)
                        ],
                        'min_rating' => 'sometimes|required|integer|min:0',
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
