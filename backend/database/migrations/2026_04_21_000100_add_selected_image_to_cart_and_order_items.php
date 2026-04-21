<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cart_items', 'selected_image')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->text('selected_image')->nullable()->after('product_id');
            });
        }

        if (! Schema::hasColumn('cart_items', 'selected_image_hash')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->string('selected_image_hash', 64)->default('')->after('selected_image');
            });
        }

        if (! Schema::hasColumn('order_items', 'selected_image')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('selected_image')->nullable()->after('product_id');
            });
        }

        $productImages = DB::table('products')->pluck('image', 'id');

        DB::table('cart_items')
            ->orderBy('id')
            ->chunkById(100, function ($items) use ($productImages): void {
                foreach ($items as $item) {
                    $selectedImage = (string) $productImages->get($item->product_id, '');

                    DB::table('cart_items')
                        ->where('id', $item->id)
                        ->update([
                            'selected_image' => $selectedImage,
                            'selected_image_hash' => hash('sha256', $selectedImage),
                        ]);
                }
            });

        DB::table('order_items')
            ->orderBy('id')
            ->chunkById(100, function ($items) use ($productImages): void {
                foreach ($items as $item) {
                    DB::table('order_items')
                        ->where('id', $item->id)
                        ->update([
                            'selected_image' => (string) $productImages->get($item->product_id, ''),
                        ]);
                }
            });

        $this->ensureCartItemForeignKeyIndexes();

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_id_product_id_unique');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'product_id', 'selected_image_hash'],
                'cart_items_user_product_selected_image_hash_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_product_selected_image_hash_unique');
            $table->dropColumn(['selected_image', 'selected_image_hash']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('selected_image');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });
    }

    private function ensureCartItemForeignKeyIndexes(): void
    {
        try {
            DB::statement('ALTER TABLE `cart_items` ADD INDEX `cart_items_user_id_index` (`user_id`)');
        } catch (\Throwable) {
            // Index already exists.
        }

        try {
            DB::statement('ALTER TABLE `cart_items` ADD INDEX `cart_items_product_id_index` (`product_id`)');
        } catch (\Throwable) {
            // Index already exists.
        }
    }
};
