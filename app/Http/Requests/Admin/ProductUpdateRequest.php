<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'published' => $this->boolean('published'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string', 'min:1', 'max:500'],
            'cost' => ['numeric'],
            'price' => ['numeric'],
            'published' => ['boolean'],
            'category_id' => ['exists:categories,id'],
        ];
    }
}
