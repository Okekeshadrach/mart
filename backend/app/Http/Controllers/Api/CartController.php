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
use Illuminate\Support\Facades\DB;

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
        $selectedImage = $product->normalizeSelectedImage($request->string('selected_image')->toString());
        $selectedImageHash = hash('sha256', (string) $selectedImage);

        if (! $product->in_stock) {
            return response()->json([
                'message' => 'This product is currently out of stock.',
            ], 422);
        }

        $cartItem = CartItem::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'selected_image_hash' => $selectedImageHash,
        ]);

        $cartItem->selected_image = $selectedImage;
        $cartItem->selected_image_hash = $selectedImageHash;
        $cartItem->quantity = ($cartItem->quantity ?? 0) + $request->integer('quantity');
        $cartItem->save();

        return CartItemResource::make($cartItem->load('product.category'))
            ->response()
            ->setStatusCode($cartItem->wasRecentlyCreated ? 201 : 200);
    }

    public function merge(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer'],
            'items.*.productId' => ['nullable', 'integer'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.qty' => ['nullable', 'integer', 'min:1'],
            'items.*.selected_image' => ['nullable', 'string', 'max:2048'],
            'items.*.selectedImage' => ['nullable', 'string', 'max:2048'],
        ]);

        $items = collect($validated['items'])
            ->map(function (array $item): array {
                return [
                    'product_id' => (int) ($item['product_id'] ?? $item['productId'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? $item['qty'] ?? 1),
                    'selected_image' => $item['selected_image'] ?? $item['selectedImage'] ?? null,
                ];
            })
            ->filter(fn (array $item): bool => $item['product_id'] > 0 && $item['quantity'] > 0)
            ->values();

        $products = Product::query()
            ->whereIn('id', $items->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $normalizedItems = [];
        $failedItems = [];
        $mergedItemCount = 0;
        $mergedQuantity = 0;

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                $failedItems[] = [
                    'productId' => $item['product_id'],
                    'message' => 'Product not found.',
                ];

                continue;
            }

            if (! $product->in_stock) {
                $failedItems[] = [
                    'productId' => $product->id,
                    'message' => 'This product is currently out of stock.',
                ];

                continue;
            }

            $selectedImage = $product->normalizeSelectedImage($item['selected_image']);
            $selectedImageHash = hash('sha256', (string) $selectedImage);
            $normalizedKey = $product->id.'::'.$selectedImageHash;

            if (! isset($normalizedItems[$normalizedKey])) {
                $normalizedItems[$normalizedKey] = [
                    'product_id' => $product->id,
                    'quantity' => 0,
                    'selected_image' => $selectedImage,
                    'selected_image_hash' => $selectedImageHash,
                ];
            }

            $normalizedItems[$normalizedKey]['quantity'] += $item['quantity'];
        }

        DB::transaction(function () use ($normalizedItems, $request, &$mergedItemCount, &$mergedQuantity): void {
            foreach ($normalizedItems as $item) {
                $cartItem = CartItem::query()->firstOrNew([
                    'user_id' => $request->user()->id,
                    'product_id' => $item['product_id'],
                    'selected_image_hash' => $item['selected_image_hash'],
                ]);

                $cartItem->selected_image = $item['selected_image'];
                $cartItem->selected_image_hash = $item['selected_image_hash'];
                $cartItem->quantity = ($cartItem->quantity ?? 0) + $item['quantity'];
                $cartItem->save();

                $mergedItemCount++;
                $mergedQuantity += $item['quantity'];
            }
        });

        return response()->json([
            'message' => 'Guest cart merge completed.',
            'mergedItemCount' => $mergedItemCount,
            'mergedQuantity' => $mergedQuantity,
            'failedItems' => $failedItems,
        ]);
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
