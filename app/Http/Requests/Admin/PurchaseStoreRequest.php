<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStoreRequest extends FormRequest
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
            'paid' => ['required', 'boolean'],
            'delivery_date' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'store_id' => ['required', 'exists:stores,id'],
        ];
    }
}
