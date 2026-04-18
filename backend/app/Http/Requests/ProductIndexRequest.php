<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', 'max:255'],
            'min_rating' => ['nullable', 'numeric', 'between:1,5'],
            'search' => ['nullable', 'string', 'max:255'],
            'sort' => [
                'nullable',
                'string',
                Rule::in(['featured', 'price-asc', 'price_asc', 'price-desc', 'price_desc', 'newest']),
            ],
        ];
    }
}
