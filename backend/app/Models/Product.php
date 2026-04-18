<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'original_price',
        'rating',
        'review_count',
        'image',
        'images',
        'description',
        'features',
        'in_stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'original_price' => 'float',
            'rating' => 'float',
            'images' => 'array',
            'features' => 'array',
            'in_stock' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function refreshRatingSummary(): void
    {
        $this->forceFill([
            'rating' => (float) round((float) $this->reviews()->avg('rating'), 2),
            'review_count' => $this->reviews()->count(),
        ])->save();
    }
}
