<?php

namespace App\Http\Requests\Admin;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Review::class);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'approved' => $this->boolean('approved'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'body' => ['required', 'string', 'min:1'],
            'approved' => ['boolean']
        ];
    }
}
