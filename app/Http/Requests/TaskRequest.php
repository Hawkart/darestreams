<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Enums\TaskStatus;
use BenSampo\Enum\Rules\EnumValue;

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
                        'stream_id' => 'sometimes|required|exists:streams,id',
                        'created_amount' => 'required|numeric|min:0',
                        'small_desc' => 'required|string|min:1',
                        'full_desc' => 'required|string|min:1',
                        'interval_time' => 'sometimes|required|numeric|min:0'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'status' => ['sometimes', 'required', new EnumValue(TaskStatus::class)],
                        'small_desc' => 'sometimes|required|string|min:1',
                        'full_desc' => 'sometimes|required|string|min:1',
                        'interval_time' => 'sometimes|required|numeric|min:0'
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
