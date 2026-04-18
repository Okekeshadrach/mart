<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with('category')
            ->latest()
            ->get();

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $product = Product::create($request->validated());

        return ProductResource::make($product->load('category'))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        return ProductResource::make($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update(collect($request->validated())->filter(function ($value, $key) {
            return $value !== null || in_array($key, ['original_price', 'images', 'features'], true);
        })->all());

        return ProductResource::make($product->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
        } catch (QueryException) {
            return response()->json([
                'message' => 'Product cannot be deleted because it is referenced by an existing order.',
            ], 409);
        }

        return response()->json([], 204);
    }
}
