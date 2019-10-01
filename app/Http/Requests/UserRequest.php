<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest {
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
        $user = auth()->user();

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'PUT':
            case 'PATCH':
                {
                    $data = [
                        'name' => 'sometimes|required|string|max:255',
                        'first_name' => 'sometimes|required|string|max:255',
                        'last_name' => 'sometimes|required|string|max:255',
                        //'nickname' => 'required|unique:users,nickname,'.$user->id
                    ];

                    if(!$user->hasVerifiedEmail())
                        $data['email'] = 'required|email|unique:users,email,'.$user->id;

                    return $data;
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
