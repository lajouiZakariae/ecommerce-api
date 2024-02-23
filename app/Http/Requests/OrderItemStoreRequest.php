<?php

namespace App\Http\Requests;

use App\Rules\ValidIntegerTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required', new ValidIntegerTypeRule, 'min:1', 'distinct', 'exists:products,id'
            ],
            'quantity' => ['required', new ValidIntegerTypeRule, 'min:1'],
        ];
    }
}