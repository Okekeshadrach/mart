<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public static function usesExternalImagePath(?string $path): bool
    {
        return filled($path) && Str::startsWith($path, ['http://', 'https://']);
    }

    public static function resolveImageUrlFromPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (static::usesExternalImagePath($path)) {
            return $path;
        }

        return Storage::disk(config('filesystems.product_images_disk', 'r2'))->url($path);
    }

    public function resolvePrimaryImageUrl(): ?string
    {
        return static::resolveImageUrlFromPath($this->image);
    }

    /**
     * @return array<int, string>
     */
    public function resolveGalleryImagePaths(): array
    {
        return collect($this->images ?: [])
            ->filter()
            ->values()
            ->all();
    }

    public function normalizeSelectedImage(?string $selectedImage): ?string
    {
        if (blank($selectedImage)) {
            return $this->image;
        }

        foreach ($this->resolveGalleryImagePaths() as $path) {
            if ($selectedImage === $path || $selectedImage === static::resolveImageUrlFromPath($path)) {
                return $path;
            }
        }

        if ($selectedImage === $this->image || $selectedImage === $this->resolvePrimaryImageUrl()) {
            return $this->image;
        }

        return $this->image;
    }

    /**
     * @return array<int, string>
     */
    public function resolveGalleryImageUrls(): array
    {
        $paths = $this->resolveGalleryImagePaths();

        if ($paths === []) {
            $paths = array_filter([$this->image]);
        }

        return collect($paths)
            ->map(fn (?string $path) => static::resolveImageUrlFromPath($path))
            ->filter()
            ->values()
            ->all();
    }
}
