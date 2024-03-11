<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Rules\ValidIntegerTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'exists:clients,id'],
            'coupon_code_id' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'exists:coupon_codes,id'],
            'payment_method_id' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'exists:payment_methods,id'],

            'order_items' => ['present', 'array', 'min:1'],

            'order_items.*.product_id' => [
                'required', 'integer', new ValidIntegerTypeRule, 'min:1', 'distinct', 'exists:products,id'
            ],
            'order_items.*.quantity' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1'],
        ];
    }
}
