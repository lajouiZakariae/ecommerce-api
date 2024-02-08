<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
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
            'email' => ['required', 'string', 'min:1', 'max:255'],
            'body' => ['required', 'string', 'min:1'],
            'product_id' => ['required', 'exists:products,id'],
            'approved' => ['required', 'boolean']
        ];
    }
}
