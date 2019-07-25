<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StreamRequest extends FormRequest {
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
                        'game_id'  => 'required|exists:games,id',
                        'link'     => 'required|url',
                        'start_at' => 'required|date|after:now'
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
