<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->relationLoaded('product') ? $this->product : null;
        $category = $product && $product->relationLoaded('category') ? $product->category : null;
        $price = $product ? (float) $product->price : 0.0;

        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'slug' => $product?->slug,
            'name' => $product?->name,
            'category' => $category?->name,
            'price' => $price,
            'image' => $product?->image,
            'quantity' => (int) $this->quantity,
            'qty' => (int) $this->quantity,
            'lineTotal' => round($price * (int) $this->quantity, 2),
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
