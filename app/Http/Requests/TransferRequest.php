<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([auth()->id()]), // Cannot send to self
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/', // 2 decimal places max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.exists' => 'The selected receiver does not exist.',
            'receiver_id.not_in' => 'You cannot transfer money to yourself.',
            'amount.min' => 'The amount must be at least 0.01.',
            'amount.regex' => 'The amount must have a maximum of 2 decimal places.',
        ];
    }
}
