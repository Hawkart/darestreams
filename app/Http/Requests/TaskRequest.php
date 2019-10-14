<?php

namespace App\Http\Requests;

use App\Rules\ValidCanTaskCreate;
use App\Rules\ValidTaskCanAdvCreate;
use App\Rules\ValidTaskCreatedEnoughMoneyAndMinAmount;
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
                return [
                    'stream_id' => 'required|exists:streams,id'
                ];
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'stream_id' => 'required|exists:streams,id',
                        'created_amount' => [
                            'required_without:adv_task_id',
                            'numeric',
                            'min:0',
                            new ValidTaskCreatedEnoughMoneyAndMinAmount($this->get('stream_id')),
                            new ValidCanTaskCreate($this->get('stream_id'))
                        ],
                        'small_desc' => 'required_without:adv_task_id|string|min:1',
                        'full_desc' => 'required_without:adv_task_id|string|min:1',
                        'interval_time' => 'sometimes|required_without:adv_task_id|numeric|min:0',
                        'adv_task_id' => [
                            'sometimes',
                            'exists:adv_tasks,id',
                            new ValidTaskCanAdvCreate($this->get('stream_id'), $this->get('adv_task_id'))
                        ]
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
