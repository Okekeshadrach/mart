<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category_id' => $this->input('category_id', $this->input('categoryId')),
            'original_price' => $this->input('original_price', $this->input('originalPrice')),
            'in_stock' => $this->input('in_stock', $this->input('inStock')),
            'slug' => $this->input('slug') ?: Str::slug((string) $this->input('name')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['required', 'string', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:2048'],
            'description' => ['required', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'in_stock' => ['sometimes', 'boolean'],
        ];
    }
}
