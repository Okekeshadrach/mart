<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'selected_image',
        'selected_image_hash',
        'quantity',
    ];

    protected static function booted(): void
    {
        static::saving(function (CartItem $cartItem): void {
            if (blank($cartItem->selected_image) && $cartItem->product_id) {
                $product = $cartItem->relationLoaded('product')
                    ? $cartItem->product
                    : Product::query()->find($cartItem->product_id);

                $cartItem->selected_image = $product?->image;
            }

            $cartItem->selected_image_hash = hash('sha256', (string) $cartItem->selected_image);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
