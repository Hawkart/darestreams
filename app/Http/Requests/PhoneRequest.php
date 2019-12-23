<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest {

    public function rules()
    {
       return [
            'phone' => 'required|numeric|digits_between:9,12'
       ];
    }

    public function messages()
    {
        return [
        ];
    }
}