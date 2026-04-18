<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'total' => (float) $this->total,
            'status' => $this->status?->value ?? $this->status,
            'shippingAddress' => $this->shipping_address,
            'paymentMethod' => $this->payment_method?->value ?? $this->payment_method,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
