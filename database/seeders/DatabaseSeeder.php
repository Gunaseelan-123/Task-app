<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        ProductVariant::query()->delete();
        ProductImage::query()->delete();
        Product::query()->delete();
        Category::query()->where('slug', '!=', 'accessories')->delete();

        $accessoryCategory = Category::query()->updateOrCreate(
            ['slug' => 'accessories'],
            [
                'name' => 'Accessories',
                'description' => 'Premium accessories and everyday essentials for modern living.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $items = [
            ['HyperCharge Power Bank', 'HyperCharge', 2499, 'power bank'],
            ['Pulse Wireless Earbuds', 'Pulse', 6999, 'earbuds'],
            ['Orbit Bluetooth Headset', 'Orbit', 4999, 'headset'],
            ['Nova MagSafe Phone Case', 'Nova', 1799, 'phone case'],
            ['Glide Ergonomic Mouse', 'Glide', 2299, 'wireless mouse'],
            ['Spark USB-C Charger', 'Spark', 1599, 'usb c charger'],
            ['Luma Smart Watch Band', 'Luma', 1299, 'watch band'],
            ['Zen Travel Adapter', 'Zen', 1199, 'travel adapter'],
            ['Arc Laptop Sleeve', 'Arc', 2499, 'laptop sleeve'],
            ['Vault RFID Wallet', 'Vault', 2199, 'wallet'],
            ['Clarity Screen Protector', 'Clarity', 799, 'screen protector'],
            ['Flex USB Cable Pack', 'Flex', 999, 'usb cable'],
            ['Beam Clip-on Ring Light', 'Beam', 1899, 'ring light'],
            ['Trackly Smart Key Finder', 'Trackly', 2399, 'key finder'],
            ['Studio Stylus Pen', 'Studio', 1999, 'stylus pen'],
            ['Aura Wireless Speaker', 'Aura', 4899, 'portable speaker'],
            ['Pulse Fitness Band', 'Pulse', 3499, 'fitness band'],
            ['Metro Desk Organizer', 'Metro', 1499, 'desk organizer'],
            ['Nimbus Sleep Mask', 'Nimbus', 999, 'sleep mask'],
            ['Solis Sunglasses Case', 'Solis', 1299, 'sunglasses case'],
            ['Luxe Jewelry Travel Box', 'Luxe', 2799, 'jewelry box'],
            ['Cloud Noise Cancelling Earbuds', 'Cloud', 8999, 'noise cancelling earbuds'],
            ['Glow LED Light Strip', 'Glow', 1799, 'led light strip'],
            ['Shield Laptop Stand', 'Shield', 2999, 'laptop stand'],
            ['Volt Magnetic Car Mount', 'Volt', 1899, 'car mount'],
            ['PureTone Audio Cable', 'PureTone', 1299, 'audio cable'],
            ['Aero Bluetooth Tracker', 'Aero', 2199, 'bluetooth tracker'],
            ['Pulse Sports Armband', 'Pulse', 999, 'sports armband'],
            ['Stream Wireless Adapter', 'Stream', 1999, 'wireless adapter'],
            ['Prime Gaming Headset', 'Prime', 7999, 'gaming headset'],
        ];

        $badges = ['Best Seller', 'New Arrival', 'Limited Deal', 'Top Rated', 'Premium Pick', 'Customer Favorite'];
        $deliveryEtas = ['Same day dispatch', 'Tomorrow delivery', 'Delivery in 2 days', 'Delivery in 48 hours', 'Delivery by Friday'];

        $sequence = 1001;
        foreach ($items as [$name, $brand, $basePrice, $imageQuery]) {
            $sku = 'NS-'.$sequence;
            $price = $basePrice + (($sequence % 5) * 120);
            $comparePrice = $price + max(300, (int) round($price * (0.12 + (($sequence % 3) * 0.04))));
            $rating = number_format(4.2 + (($sequence % 7) / 10), 1);
            $badge = $badges[$sequence % count($badges)];
            $deliveryEta = $deliveryEtas[$sequence % count($deliveryEtas)];

            $product = Product::query()->updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $accessoryCategory->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'brand' => $brand,
                    'short_description' => 'Premium '.$name.' built for modern everyday use.',
                    'description' => $name.' delivers reliable performance, polished design, and a premium accessories experience.',
                    'price' => $price,
                    'compare_price' => $comparePrice,
                    'stock' => 15 + ($sequence % 70),
                    'rating' => $rating,
                    'badge_text' => $badge,
                    'delivery_eta' => $deliveryEta,
                    'search_keywords' => strtolower($brand.' '.$name.' accessories premium'),
                    'is_featured' => $sequence % 3 === 0,
                    'is_active' => true,
                    'meta_title' => $name.' | Northstar Commerce',
                    'meta_description' => 'Buy '.$name.' from Northstar Commerce.',
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                [
                    'path' => 'https://loremflickr.com/900/900/'.urlencode($imageQuery.',accessory').'?lock='.$sequence,
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

            $sequence++;
        }

        ProductImage::query()->whereNotIn('product_id', Product::query()->pluck('id'))->delete();
        ProductVariant::query()->whereNotIn('product_id', Product::query()->pluck('id'))->delete();
    }
}
