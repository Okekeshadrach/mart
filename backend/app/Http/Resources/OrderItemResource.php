<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->relationLoaded('product') ? $this->product : null;

        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'quantity' => (int) $this->quantity,
            'price' => (float) $this->price,
            'lineTotal' => round((float) $this->price * (int) $this->quantity, 2),
            'productName' => $product?->name,
            'productSlug' => $product?->slug,
            'productImage' => $product?->image,
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
