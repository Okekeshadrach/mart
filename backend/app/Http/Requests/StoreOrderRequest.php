<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('shipping_address')) {
            $this->merge([
                'shipping_address' => [
                    'first_name' => $this->input('first_name'),
                    'last_name' => $this->input('last_name'),
                    'email' => $this->input('email'),
                    'address' => $this->input('address'),
                    'city' => $this->input('city'),
                    'zip' => $this->input('zip'),
                    'country' => $this->input('country'),
                ],
            ]);
        }

        $this->merge([
            'payment_method' => $this->input('payment_method', $this->input('payment')),
        ]);
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'shipping_address' => ['required', 'array'],
            'shipping_address.first_name' => ['required', 'string', 'max:255'],
            'shipping_address.last_name' => ['required', 'string', 'max:255'],
            'shipping_address.email' => ['required', 'email', 'max:255'],
            'shipping_address.address' => ['required', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:255'],
            'shipping_address.zip' => ['required', 'string', 'max:50'],
            'shipping_address.country' => ['required', 'string', 'max:255'],
        ];
    }
}
