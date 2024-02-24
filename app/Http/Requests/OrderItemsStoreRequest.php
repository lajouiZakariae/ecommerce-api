<?php

namespace App\Http\Requests;

use App\Rules\ValidIntegerTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderItemsStoreRequest extends FormRequest
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
            'order_items' => ['present', 'array', 'min:1'],

            'order_items.*.product_id' => [
                'required',
                new ValidIntegerTypeRule,
                'min:1',
                'distinct',
                'exists:products,id',
                Rule::unique('order_items', 'product_id')->where('order_id', request()->route('order'))
            ],
            'order_items.*.quantity' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1'],
        ];
    }
}
