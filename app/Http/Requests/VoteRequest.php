<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Enums\VoteStatus;
use BenSampo\Enum\Rules\EnumValue;

class VoteRequest extends FormRequest {
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
        return [
            'vote' => ['required', new EnumValue(VoteStatus::class)],
        ];
    }


    public function messages()
    {
        return [
        ];
    }
}
