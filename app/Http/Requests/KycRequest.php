<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class KycRequest extends FormRequest {

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
                    'phone' => 'required|numeric|digits_between:9,12'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {

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