<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewUpdateRequest extends FormRequest
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
            'email' => ['string', 'min:1', 'max:255'],
            'body' => ['string', 'min:1'],
            'product_id' => ['exists:products,id'],
            'approved' => ['boolean']
        ];
    }
}
