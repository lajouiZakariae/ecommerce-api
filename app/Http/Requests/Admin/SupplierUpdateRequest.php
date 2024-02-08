<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
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
            'name' => ['string', 'min:1', 'max:255'],
            'email' => ['email', 'string', 'min:1', 'max:255'],
            'phone_number' => ['string', 'min:1', 'max:255'],
            'address' => ['string', 'min:1', 'max:255']
        ];
    }
}
