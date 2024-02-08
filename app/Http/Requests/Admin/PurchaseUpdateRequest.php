<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
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
            'paid' => ['boolean'],
            'delivery_date' => ['date'],
            'supplier_id' => ['exists:suppliers,id'],
            'payment_method_id' => ['exists:payment_methods,id'],
            'store_id' => ['exists:stores,id'],
        ];
    }
}
