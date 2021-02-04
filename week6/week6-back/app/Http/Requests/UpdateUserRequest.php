<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
    public function rules()
    {
        return [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notification_period' => 'required|in:none,month,quarter',
            'image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'currencies' => [
                'array',
                'required'
            ],
            'currencies.*.currency' => 'required|exists:user_currencies,currency',
            'currencies.*.force_exchange' => 'required|boolean',
            'currencies.*.force_exchange_amount' => 'required|numeric|min:0|max:100'
        ];
    }
}
