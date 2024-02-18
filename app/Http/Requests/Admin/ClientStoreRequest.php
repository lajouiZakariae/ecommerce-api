<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'phone_number' => ['required', 'string', 'min:1', 'max:255'],
            'city' => ['required', 'string', 'min:1', 'max:255'],
            'zip_code' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
