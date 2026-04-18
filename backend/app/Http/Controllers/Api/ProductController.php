<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $products = Product::query()->with('category');

        if ($categoryFilter = $request->validated('category')) {
            $filters = collect(explode(',', $categoryFilter))
                ->map(fn (string $value) => trim($value))
                ->filter()
                ->values();

            $products->whereHas('category', function ($query) use ($filters) {
                $query->whereIn('slug', $filters)->orWhereIn('name', $filters);
            });
        }

        if ($minRating = $request->validated('min_rating')) {
            $products->where('rating', '>=', $minRating);
        }

        if ($search = $request->validated('search')) {
            $products->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$search}%"));
            });
        }

        match ($request->validated('sort', 'featured')) {
            'price-asc', 'price_asc' => $products->orderBy('price'),
            'price-desc', 'price_desc' => $products->orderByDesc('price'),
            'newest' => $products->latest(),
            default => $products->orderByDesc('review_count')->orderByDesc('rating'),
        };

        return ProductResource::collection($products->get());
    }

    public function show(Product $product): ProductResource
    {
        $product->load([
            'category',
            'reviews' => fn ($query) => $query->with('user')->latest()->limit(10),
        ]);

        return ProductResource::make($product);
    }
}
