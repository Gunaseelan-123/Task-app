<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku')->unique();
                $table->string('brand')->nullable();
                $table->string('short_description')->nullable();
                $table->longText('description');
                $table->decimal('price', 10, 2);
                $table->decimal('compare_price', 10, 2)->nullable();
                $table->unsignedInteger('stock')->default(0);
                $table->decimal('rating', 3, 1)->default(0);
                $table->boolean('is_featured')->default(false)->index();
                $table->boolean('is_active')->default(true)->index();
                $table->string('meta_title')->nullable();
                $table->string('meta_description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->string('alt_text')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('sku')->unique();
                $table->string('size')->nullable();
                $table->string('color')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->unsignedInteger('stock')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('image_url');
                $table->string('cta_label')->nullable();
                $table->string('cta_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('type', ['percentage', 'flat']);
                $table->decimal('value', 10, 2);
                $table->decimal('minimum_amount', 10, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['home', 'office', 'other'])->default('home');
                $table->string('full_name');
                $table->string('phone');
                $table->string('line_1');
                $table->string('line_2')->nullable();
                $table->string('city');
                $table->string('state');
                $table->string('postal_code');
                $table->string('country');
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
                $table->unsignedInteger('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
                $table->string('order_number')->unique();
                $table->string('status')->default('placed')->index();
                $table->string('payment_status')->default('pending');
                $table->string('payment_method');
                $table->string('coupon_code')->nullable();
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount_total', 10, 2)->default(0);
                $table->decimal('shipping_total', 10, 2)->default(0);
                $table->decimal('tax_total', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2);
                $table->timestamp('placed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->string('product_name');
                $table->string('sku')->nullable();
                $table->decimal('unit_price', 10, 2);
                $table->unsignedInteger('quantity');
                $table->decimal('line_total', 10, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->string('title')->nullable();
                $table->text('body')->nullable();
                $table->boolean('is_verified_purchase')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wishlist_items')) {
            Schema::create('wishlist_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'product_id']);
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
