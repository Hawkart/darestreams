<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquireRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:1',
            'name' => 'required|string|min:1',
            'phone' => 'required|numeric|digits_between:9,12',
            'email' => 'required|email',
        ];
    }


    public function messages()
    {
        return [
        ];
    }
}