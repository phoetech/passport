<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SendVerificationCode extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO: 这个接口的滥用问题
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ccc' => 'required|digits_between:1,5',
            'phone' => 'required|digits_between:6,16',
        ];
    }

    public function messages()
    {
        return [
            'ccc.required' => 'The :attribute is required.',
            'phone.required' => 'The :attribute is required.',

            // 'ccc.digits' => 'The :attribute is incorrect.',
            // 'phone.digits' => 'The :attribute is incorrect.',

            'ccc.digits_between' => 'The :attribute value :input is not between :min - :max.',
            'phone.digits_between' => 'The :attribute value :input is not between :min - :max.',
        ];
    }
}
