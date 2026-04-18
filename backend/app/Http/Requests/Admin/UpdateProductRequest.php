<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        if ($slug === null && $this->filled('name')) {
            $slug = Str::slug((string) $this->input('name'));
        }

        $this->merge([
            'category_id' => $this->input('category_id', $this->input('categoryId')),
            'original_price' => $this->input('original_price', $this->input('originalPrice')),
            'in_stock' => $this->input('in_stock', $this->input('inStock')),
            'slug' => $slug,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['sometimes', 'string', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:2048'],
            'description' => ['sometimes', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'in_stock' => ['sometimes', 'boolean'],
        ];
    }
}
