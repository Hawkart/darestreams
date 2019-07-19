<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;
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
        $user = Auth::user();

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
                        'title'  => 'required|string|max:255',
                        'description' => 'required|string|min:10'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'description' => 'required|string|min:10'
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
