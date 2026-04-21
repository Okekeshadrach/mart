<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $category = $this->relationLoaded('category') ? $this->category : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'categoryId' => $this->category_id,
            'category' => $category?->name,
            'categorySlug' => $category?->slug,
            'categoryData' => CategoryResource::make($this->whenLoaded('category')),
            'price' => (float) $this->price,
            'originalPrice' => $this->original_price !== null ? (float) $this->original_price : null,
            'rating' => (float) $this->rating,
            'reviewCount' => (int) $this->review_count,
            'image' => $this->resolvePrimaryImageUrl(),
            'images' => $this->resolveGalleryImageUrls(),
            'description' => $this->description,
            'features' => $this->features ?? [],
            'inStock' => (bool) $this->in_stock,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
