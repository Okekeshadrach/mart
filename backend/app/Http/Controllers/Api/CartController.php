<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $request->user()
            ->cartItems()
            ->with('product.category')
            ->get();

        return CartItemResource::collection($items);
    }

    public function store(StoreCartItemRequest $request): JsonResponse
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));

        if (! $product->in_stock) {
            return response()->json([
                'message' => 'This product is currently out of stock.',
            ], 422);
        }

        $cartItem = CartItem::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = ($cartItem->quantity ?? 0) + $request->integer('quantity');
        $cartItem->save();

        return CartItemResource::make($cartItem->load('product.category'))
            ->response()
            ->setStatusCode($cartItem->wasRecentlyCreated ? 201 : 200);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->update([
            'quantity' => $request->integer('quantity'),
        ]);

        return CartItemResource::make($cartItem->load('product.category'));
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([], 204);
    }
}
