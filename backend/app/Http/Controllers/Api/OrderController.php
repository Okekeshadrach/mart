<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items.product.category')
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $cartItems = $request->user()
            ->cartItems()
            ->with('product.category')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        if ($cartItems->contains(fn ($item) => ! $item->product || ! $item->product->in_stock)) {
            return response()->json([
                'message' => 'One or more products in your cart are out of stock.',
            ], 422);
        }

        $order = DB::transaction(function () use ($request, $cartItems) {
            $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'status' => OrderStatus::Pending,
                'shipping_address' => $request->validated('shipping_address'),
                'payment_method' => $request->validated('payment_method'),
            ]);

            $order->items()->createMany(
                $cartItems->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'selected_image' => $item->selected_image ?: $item->product?->image,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ])->all()
            );

            $request->user()->cartItems()->delete();

            return $order;
        });

        $order->load('items.product.category', 'user');

        rescue(
            fn () => Mail::to($order->user)->send(new OrderConfirmationMail($order)),
            report: true,
        );

        return OrderResource::make($order)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Order $order): OrderResource|JsonResponse
    {
        if (! $request->user()->isAdmin() && $order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return OrderResource::make($order->load('items.product.category'));
    }
}
