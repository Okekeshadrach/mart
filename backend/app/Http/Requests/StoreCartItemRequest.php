<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'product_id' => $this->input('product_id', $this->input('productId')),
            'quantity' => $this->input('quantity', $this->input('qty', 1)),
            'selected_image' => $this->input('selected_image', $this->input('selectedImage')),
        ]);
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'selected_image' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
