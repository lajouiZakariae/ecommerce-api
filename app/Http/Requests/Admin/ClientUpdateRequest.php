<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'min:1', 'max:255'],
            'last_name' => ['string', 'min:1', 'max:255'],
            'email' => ['email', 'min:1', 'max:255'],
            'phone_number' => ['string', 'min:1', 'max:255'],
            'city' => ['string', 'min:1', 'max:255'],
            'zip_code' => ['string', 'min:1', 'max:255'],
        ];
    }
}
