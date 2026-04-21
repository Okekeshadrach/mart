<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->relationLoaded('product') ? $this->product : null;
        $category = $product && $product->relationLoaded('category') ? $product->category : null;
        $price = $product ? (float) $product->price : 0.0;
        $selectedImage = $this->selected_image ?: $product?->image;

        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'slug' => $product?->slug,
            'name' => $product?->name,
            'category' => $category?->name,
            'price' => $price,
            'image' => Product::resolveImageUrlFromPath($selectedImage),
            'selectedImage' => Product::resolveImageUrlFromPath($selectedImage),
            'quantity' => (int) $this->quantity,
            'qty' => (int) $this->quantity,
            'lineTotal' => round($price * (int) $this->quantity, 2),
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
