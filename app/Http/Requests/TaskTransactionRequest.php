<?php

namespace App\Http\Requests;

use App\Rules\ValidAmountDonation;
use App\Rules\ValidTaskStatusForDonation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
                        'amount' => [
                            'required',
                            'integer',
                            'min:1',
                            new ValidAmountDonation($request->route('task')),
                            new ValidTaskStatusForDonation($request->route('task'))
                        ]
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
