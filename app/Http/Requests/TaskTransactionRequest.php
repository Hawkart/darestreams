<?php

namespace App\Http\Requests;

use App\Rules\ValidAmountDonation;
use App\Rules\ValidTaskStatusForDonation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TaskTransactionRequest extends FormRequest {
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
        $task = $request->route('task');

        return [
            'amount' => [
                'required',
                'integer',
                'min:1',
                new ValidAmountDonation($task),
                new ValidTaskStatusForDonation($task)
            ]
        ];
    }


    public function messages()
    {
        return [
        ];
    }
}
