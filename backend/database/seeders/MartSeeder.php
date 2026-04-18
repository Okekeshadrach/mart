<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class MartSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::current();

        User::updateOrCreate(
            ['email' => 'admin@mart.test'],
            [
                'name' => 'MART Admin',
                'password' => 'password',
                'role' => UserRole::Admin,
            ]
        );

        $sampleCustomer = User::updateOrCreate(
            ['email' => 'customer@mart.test'],
            [
                'name' => 'Sample Customer',
                'password' => 'password',
                'role' => UserRole::Customer,
            ]
        );

        $reviewUsers = Collection::times(320, function (int $number) {
            return User::updateOrCreate(
                ['email' => "shopper{$number}@mart.test"],
                [
                    'name' => "Shopper {$number}",
                    'password' => 'password',
                    'role' => UserRole::Customer,
                ]
            );
        })->prepend($sampleCustomer)->values();

        $categories = collect([
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Beauty', 'slug' => 'beauty'],
        ])->mapWithKeys(function (array $category) {
            $model = Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );

            return [$category['slug'] => $model];
        });

        $products = collect($this->products())->map(function (array $product) use ($categories) {
            $category = $categories[$product['category_slug']];

            unset($product['category_slug']);

            return Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product + ['category_id' => $category->id]
            );
        });

        Review::query()->delete();

        foreach ($products as $index => $product) {
            $definition = $this->products()[$index];
            $reviewCount = $definition['review_count'];
            $rating = (int) round((float) $definition['rating']);

            $reviewUsers->take($reviewCount)->each(function (User $user, int $reviewIndex) use ($product, $rating) {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => $rating,
                    'comment' => $this->reviewComment($product->name, $rating, $reviewIndex),
                ]);
            });

            $product->refreshRatingSummary();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function products(): array
    {
        return [
            [
                'name' => 'Wireless Headphones Pro',
                'slug' => 'wireless-headphones-pro',
                'category_slug' => 'electronics',
                'price' => 79.99,
                'original_price' => 99.99,
                'rating' => 5,
                'review_count' => 128,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1487215078519-e21cc028cb29?w=800&h=800&fit=crop',
                ],
                'description' => 'Premium wireless headphones with active noise cancellation, 40-hour battery life, and ultra-comfortable memory foam ear cushions. Crystal-clear audio with deep bass response.',
                'features' => ['Active Noise Cancellation', '40hr Battery', 'Bluetooth 5.3', 'USB-C Charging'],
                'in_stock' => true,
            ],
            [
                'name' => 'Smart Fitness Watch',
                'slug' => 'smart-fitness-watch',
                'category_slug' => 'electronics',
                'price' => 149.00,
                'original_price' => null,
                'rating' => 4,
                'review_count' => 85,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30e?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30e?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1546868871-af0de0ae72be?w=800&h=800&fit=crop',
                ],
                'description' => 'Track your health metrics with precision. Heart rate monitoring, GPS, sleep tracking, and 7-day battery life in a sleek, water-resistant design.',
                'features' => ['Heart Rate Monitor', 'GPS Tracking', 'Water Resistant', '7-Day Battery'],
                'in_stock' => true,
            ],
            [
                'name' => 'Premium Cotton T-Shirt',
                'slug' => 'premium-cotton-t-shirt',
                'category_slug' => 'clothing',
                'price' => 34.99,
                'original_price' => null,
                'rating' => 5,
                'review_count' => 203,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&h=800&fit=crop',
                ],
                'description' => 'Crafted from 100% organic Supima cotton. Pre-shrunk, breathable, and designed for all-day comfort with a modern relaxed fit.',
                'features' => ['100% Organic Cotton', 'Pre-Shrunk', 'Relaxed Fit', 'Machine Washable'],
                'in_stock' => true,
            ],
            [
                'name' => 'Organic Face Serum',
                'slug' => 'organic-face-serum',
                'category_slug' => 'beauty',
                'price' => 42.00,
                'original_price' => null,
                'rating' => 4,
                'review_count' => 67,
                'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1570194065650-d99fb4b38b17?w=800&h=800&fit=crop',
                ],
                'description' => 'Lightweight, fast-absorbing serum with Vitamin C, Hyaluronic Acid, and botanical extracts. Brightens and hydrates for a radiant complexion.',
                'features' => ['Vitamin C', 'Hyaluronic Acid', 'Vegan', 'Cruelty-Free'],
                'in_stock' => true,
            ],
            [
                'name' => 'Stainless Steel Bottle',
                'slug' => 'stainless-steel-bottle',
                'category_slug' => 'home-garden',
                'price' => 28.99,
                'original_price' => 35.00,
                'rating' => 5,
                'review_count' => 312,
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&h=800&fit=crop',
                ],
                'description' => 'Double-wall vacuum insulated bottle keeps drinks cold for 24 hours or hot for 12. BPA-free, leak-proof, and eco-friendly.',
                'features' => ['Vacuum Insulated', 'BPA-Free', 'Leak-Proof', '750ml Capacity'],
                'in_stock' => true,
            ],
            [
                'name' => 'Minimalist Desk Lamp',
                'slug' => 'minimalist-desk-lamp',
                'category_slug' => 'home-garden',
                'price' => 65.00,
                'original_price' => null,
                'rating' => 4,
                'review_count' => 94,
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057ab6fe?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057ab6fe?w=800&h=800&fit=crop',
                ],
                'description' => 'Adjustable LED desk lamp with 3 color temperatures and 10 brightness levels. Touch control with memory function and USB charging port.',
                'features' => ['LED', 'Touch Control', 'USB Charging Port', 'Adjustable Arm'],
                'in_stock' => true,
            ],
            [
                'name' => 'Running Sneakers',
                'slug' => 'running-sneakers',
                'category_slug' => 'sports',
                'price' => 119.99,
                'original_price' => 159.99,
                'rating' => 5,
                'review_count' => 178,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=800&fit=crop',
                    'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&h=800&fit=crop',
                ],
                'description' => 'Engineered mesh upper with responsive cushioning and durable rubber outsole. Lightweight design for maximum performance.',
                'features' => ['Mesh Upper', 'Responsive Cushion', 'Rubber Outsole', 'Lightweight'],
                'in_stock' => true,
            ],
            [
                'name' => 'Leather Crossbody Bag',
                'slug' => 'leather-crossbody-bag',
                'category_slug' => 'clothing',
                'price' => 89.00,
                'original_price' => null,
                'rating' => 4,
                'review_count' => 156,
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&h=800&fit=crop',
                ],
                'description' => 'Genuine leather crossbody bag with adjustable strap and multiple compartments. Timeless design that goes with everything.',
                'features' => ['Genuine Leather', 'Adjustable Strap', 'Multiple Pockets', 'Magnetic Closure'],
                'in_stock' => true,
            ],
        ];
    }

    protected function reviewComment(string $productName, int $rating, int $index): string
    {
        $comments = [
            'Great quality and exactly as described.',
            'Shipping was fast and the item feels premium.',
            'Very happy with this purchase and would buy it again.',
            'The finish and packaging were better than expected.',
            'Solid value for the price and easy to recommend.',
            'This has become one of my favorite MART purchases.',
        ];

        $comment = $comments[$index % count($comments)];

        return "{$comment} {$productName} deserves {$rating} stars.";
    }
}
