<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserPasswordUpdateRequest extends FormRequest {

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
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'password' => 'required|confirmed|min:6',
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
