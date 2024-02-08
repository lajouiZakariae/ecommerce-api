<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'full_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'string', 'min:1', 'max:255'],
            'phone_number' => ['required', 'string', 'min:1', 'max:255'],
            'status' => ['required', 'string', Rule::enum(Status::class)],
            'city' => ['required', 'string', 'min:1', 'max:255'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'zip_code' => ['required', 'string', 'min:1', 'max:255'],
            'coupon_code_id' => ['required', 'exists:coupon_codes,id'],
            'address' => ['required', 'string', 'min:1', 'max:255'],
            'delivery' => ['required', 'boolean']
        ];
    }
}
