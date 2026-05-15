<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@northstar.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'preferred_otp_channel' => 'email',
                'two_factor_enabled' => true,
            ]
        );

        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Smart devices, entertainment, and premium accessories.', 'sort_order' => 1],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Modern apparel, footwear, and travel-ready essentials.', 'sort_order' => 2],
            ['name' => 'Home Living', 'slug' => 'home-living', 'description' => 'Design-first products for contemporary homes.', 'sort_order' => 3],
            ['name' => 'Appliances', 'slug' => 'appliances', 'description' => 'Large and small appliances built for convenience.', 'sort_order' => 4],
        ];

        $categoryMap = [];

        foreach ($categories as $categoryData) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                [...$categoryData, 'is_active' => true]
            );

            $categoryMap[$categoryData['slug']] = $category;
        }

        $products = [
            ['electronics', 'Northstar X1 Smartphone', 'NS-X1', 'Northstar', 45999, 49999, 'Flagship Deal', 'Tomorrow delivery', 4.8],
            ['electronics', 'Orbit Noise Cancelling Headphones', 'NS-ORB', 'Orbit', 12999, 15999, 'Best Seller', 'Same day dispatch', 4.7],
            ['electronics', 'Aurora 4K Smart TV', 'NS-TV4K', 'Aurora', 36999, 42999, 'Festive Pick', 'Install in 24 hours', 4.6],
            ['fashion', 'Avenue Leather Weekender', 'NS-AVN', 'Avenue', 6499, 7499, 'Travel Edit', 'Delivery in 2 days', 4.4],
            ['fashion', 'Monarch Knit Polo', 'NS-MON', 'Monarch', 2499, 3299, 'Trending', 'Delivery in 2 days', 4.3],
            ['home-living', 'Halo Air Purifier', 'NS-HALO', 'Halo', 11499, 13999, 'Healthy Home', 'Delivery in 48 hours', 4.5],
            ['appliances', 'Pulse Espresso Machine', 'NS-PULSE', 'Pulse', 18999, 21999, 'Kitchen Luxe', 'Delivery by Friday', 4.5],
            ['appliances', 'Nova Front Load Washer', 'NS-NOVA', 'Nova', 28999, 32999, 'Mega Value', 'Install in 48 hours', 4.4],
        ];

        foreach ($products as [$categorySlug, $name, $sku, $brand, $price, $comparePrice, $badge, $deliveryEta, $rating]) {
            $product = Product::query()->updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $categoryMap[$categorySlug]->id,
                    'name' => $name,
                    'brand' => $brand,
                    'short_description' => 'Premium '.$name.' designed for modern shoppers.',
                    'description' => $name.' combines elegant design, strong value, and dependable performance for a production-style Laravel storefront.',
                    'price' => $price,
                    'compare_price' => $comparePrice,
                    'stock' => 25,
                    'rating' => $rating,
                    'badge_text' => $badge,
                    'delivery_eta' => $deliveryEta,
                    'search_keywords' => strtolower($brand.' '.$name.' premium ecommerce gadget fashion'),
                    'is_featured' => true,
                    'is_active' => true,
                    'meta_title' => $name.' | Northstar Commerce',
                    'meta_description' => 'Buy '.$name.' from Northstar Commerce.',
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                [
                    'path' => 'https://placehold.co/900x900/f2f6ff/14203a?text='.urlencode($name),
                    'alt_text' => $name,
                    'is_primary' => true,
                ]
            );

            ProductVariant::query()->updateOrCreate(
                ['sku' => $sku.'-STD'],
                [
                    'product_id' => $product->id,
                    'size' => 'Standard',
                    'color' => 'Black',
                    'price' => $price,
                    'stock' => 20,
                    'is_active' => true,
                ]
            );
        }

        Banner::query()->updateOrCreate(
            ['title' => 'Marketplace speed, premium storefront feel'],
            [
                'subtitle' => 'Blade-first shopping experience with admin control, secure auth, and high-conversion merchandising.',
                'image_url' => 'https://placehold.co/1600x700/e6efff/13203d?text=Northstar+Hero',
                'cta_label' => 'Shop now',
                'cta_url' => '/shop',
                'is_active' => true,
            ]
        );

        Coupon::query()->updateOrCreate(
            ['code' => 'SAVE10'],
            [
                'description' => '10 percent off on premium orders',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 1000,
                'usage_limit' => 500,
                'is_active' => true,
            ]
        );
    }
}
