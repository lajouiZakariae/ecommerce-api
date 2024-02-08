<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone_number' => ['required', 'string'],
            'status' => ['required', 'in:pending,in transit,delivered,delivery attempt,cancelled,return to sender'],
            'city' => ['required', 'string'],
            'payment_method_id' => ['required', 'integer'],
            'zip_code' => ['required', 'string'],
            'coupon_code_id' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'delivery' => ['required'],
            'order_items' => ['present', 'array'],
            'order_items.*.quantity' => ['required', 'integer'],
            'order_items.*.product_id' => ['required', 'exists:products,id'],
        ];
    }
}
