<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'selected_image',
        'quantity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $orderItem): void {
            if (blank($orderItem->selected_image) && $orderItem->product_id) {
                $product = $orderItem->relationLoaded('product')
                    ? $orderItem->product
                    : Product::query()->find($orderItem->product_id);

                $orderItem->selected_image = $product?->image;
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
