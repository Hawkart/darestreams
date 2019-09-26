<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ChannelRequest extends FormRequest {

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
                        'title' => 'required|string|max:255',
                        'game_id'  => 'required|exists:games,id',
                        'link'     => 'required|url',
                        'description' => 'required|string|max:255',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'logo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                        'game_id'  => 'sometimes|required|exists:games,id',
                        'link' => 'sometimes|required|url',
                        'description' => 'sometimes|required|string|max:255',
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
