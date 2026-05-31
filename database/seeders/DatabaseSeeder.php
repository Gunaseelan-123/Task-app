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

        $catalog = [
            'electronics' => [
                ['Northstar X1 Smartphone', 'Northstar', 45999, 'smartphone'],
                ['Orbit Noise Cancelling Headphones', 'Orbit', 12999, 'headphones'],
                ['Aurora 4K Smart TV', 'Aurora', 36999, 'smart tv'],
                ['PixelPad Pro Tablet', 'PixelPad', 28999, 'tablet'],
                ['EchoBuds Wireless Earbuds', 'EchoBuds', 5999, 'earbuds'],
                ['Volt 20000mAh Power Bank', 'Volt', 2499, 'power bank'],
                ['Luma Smartwatch Active', 'Luma', 8999, 'smartwatch'],
                ['ZenBook Air Laptop', 'ZenBook', 71999, 'laptop'],
                ['Frame Mini Projector', 'Frame', 22999, 'projector'],
                ['Nova Bluetooth Speaker', 'Nova', 4499, 'bluetooth speaker'],
                ['Clarity Web Camera', 'Clarity', 3499, 'webcam'],
                ['Swift Mechanical Keyboard', 'Swift', 5499, 'keyboard'],
                ['Glide Wireless Mouse', 'Glide', 1899, 'computer mouse'],
                ['Aero WiFi 6 Router', 'Aero', 6999, 'wifi router'],
                ['Capture Action Camera', 'Capture', 17999, 'action camera'],
                ['ProSound Soundbar', 'ProSound', 15999, 'soundbar'],
                ['ChargeDock 3-in-1 Station', 'ChargeDock', 3299, 'charging dock'],
                ['SecureView Video Doorbell', 'SecureView', 8999, 'video doorbell'],
                ['Giga Portable SSD 1TB', 'Giga', 7799, 'portable ssd'],
                ['Studio USB Microphone', 'Studio', 4999, 'usb microphone'],
                ['Arc Gaming Monitor 27', 'Arc', 24999, 'gaming monitor'],
                ['Pocket Photo Printer', 'PocketPrint', 7999, 'photo printer'],
                ['Travel GaN Charger 65W', 'TravelVolt', 2999, 'gan charger'],
                ['Sense Smart Thermostat', 'Sense', 6499, 'smart thermostat'],
                ['ViewDash Car Camera', 'ViewDash', 6499, 'dash camera'],
                ['Flex Phone Tripod', 'Flex', 1499, 'phone tripod'],
                ['Beam LED Desk Lamp', 'Beam', 2299, 'desk lamp'],
                ['Vision VR Headset', 'Vision', 27999, 'vr headset'],
                ['Wave Streaming Stick', 'Wave', 3999, 'streaming stick'],
                ['Pulse Fitness Band', 'Pulse', 3499, 'fitness band'],
                ['Snap Instant Camera', 'Snap', 8999, 'instant camera'],
                ['HomeHub Smart Display', 'HomeHub', 11999, 'smart display'],
                ['CablePro USB-C Hub', 'CablePro', 2499, 'usb c hub'],
                ['Tune Portable Radio', 'Tune', 2199, 'portable radio'],
                ['Grid Gaming Controller', 'Grid', 4999, 'game controller'],
                ['Focus Drawing Tablet', 'Focus', 7999, 'drawing tablet'],
                ['AirTag Style Tracker', 'Trackly', 1999, 'item tracker'],
                ['Volt Mini UPS Router Backup', 'Volt', 4299, 'mini ups'],
                ['ClearCall Conference Speaker', 'ClearCall', 8999, 'conference speaker'],
                ['Glow RGB Light Strip', 'Glow', 1799, 'led light strip'],
                ['Motion Smart Security Camera', 'Motion', 5499, 'security camera'],
                ['PlayBox Retro Console', 'PlayBox', 6499, 'retro console'],
                ['Reader E-Ink Book', 'Reader', 10999, 'ebook reader'],
                ['Clip Lavalier Mic Set', 'Clip', 3499, 'lavalier microphone'],
                ['Studio Monitor Headphones', 'Studio', 9999, 'studio headphones'],
                ['MagSafe Car Mount', 'MagMount', 1899, 'car phone mount'],
                ['Pixel USB-C Cable Pack', 'Pixel', 999, 'usb cable'],
                ['Drone Mini Explorer', 'SkyLite', 24999, 'camera drone'],
                ['CleanBot Robot Vacuum', 'CleanBot', 19999, 'robot vacuum'],
                ['PrintJet All-in-One Printer', 'PrintJet', 12999, 'office printer'],
            ],
            'fashion' => [
                ['Avenue Leather Weekender', 'Avenue', 6499, 'leather bag'],
                ['Monarch Knit Polo', 'Monarch', 2499, 'polo shirt'],
                ['Urban Runner Sneakers', 'Urban', 3999, 'sneakers'],
                ['CloudSoft Cotton Hoodie', 'CloudSoft', 2999, 'hoodie'],
                ['TailorFit Chino Trousers', 'TailorFit', 2799, 'chino pants'],
                ['Ridge Denim Jacket', 'Ridge', 4499, 'denim jacket'],
                ['Classic Oxford Shirt', 'Classic', 2199, 'oxford shirt'],
                ['Metro Slim Backpack', 'Metro', 3499, 'backpack'],
                ['Luxe Silk Scarf', 'Luxe', 1899, 'silk scarf'],
                ['Trail Waterproof Boots', 'Trail', 5999, 'boots'],
                ['Vista Aviator Sunglasses', 'Vista', 2499, 'sunglasses'],
                ['Everyday Crew Socks 5 Pack', 'Everyday', 799, 'socks'],
                ['Flex Gym Joggers', 'Flex', 1999, 'joggers'],
                ['Heritage Leather Belt', 'Heritage', 1499, 'leather belt'],
                ['Breeze Linen Shirt', 'Breeze', 2599, 'linen shirt'],
                ['Studio Tote Bag', 'Studio', 1999, 'tote bag'],
                ['RainShell Lightweight Jacket', 'RainShell', 3499, 'rain jacket'],
                ['Court Classic Sneakers', 'Court', 3799, 'white sneakers'],
                ['Merino Travel Tee', 'Merino', 1799, 't shirt'],
                ['Apex Formal Blazer', 'Apex', 6999, 'blazer'],
                ['Velvet Evening Dress', 'Velvet', 5499, 'evening dress'],
                ['Commuter Laptop Sleeve', 'Commuter', 1799, 'laptop sleeve'],
                ['Summit Puffer Vest', 'Summit', 3999, 'puffer vest'],
                ['Icon Baseball Cap', 'Icon', 899, 'baseball cap'],
                ['Satin Sleepwear Set', 'Satin', 3299, 'sleepwear'],
                ['RunLite Sports Bra', 'RunLite', 1599, 'sports bra'],
                ['Nomad Passport Wallet', 'Nomad', 1299, 'passport wallet'],
                ['Boardwalk Sandals', 'Boardwalk', 1499, 'sandals'],
                ['Pure Cotton Kurta', 'Pure', 2499, 'kurta'],
                ['Drape Georgette Saree', 'Drape', 3999, 'saree'],
                ['Minimal Analog Watch', 'Minimal', 2999, 'analog watch'],
                ['City Crossbody Bag', 'City', 2499, 'crossbody bag'],
                ['Thermal Winter Gloves', 'Thermal', 1199, 'winter gloves'],
                ['Active Training Shorts', 'Active', 1399, 'training shorts'],
                ['Prime Leather Loafers', 'Prime', 4999, 'loafers'],
                ['Ribbed Knit Cardigan', 'Ribbed', 2999, 'cardigan'],
                ['Canyon Cargo Pants', 'Canyon', 2599, 'cargo pants'],
                ['Ocean Swim Trunks', 'Ocean', 1699, 'swim trunks'],
                ['SoftStep Ballet Flats', 'SoftStep', 2299, 'ballet flats'],
                ['Travel Compression Cubes', 'Travel', 1799, 'packing cubes'],
                ['Arc Quilted Handbag', 'Arc', 4499, 'handbag'],
                ['Vogue Statement Earrings', 'Vogue', 1299, 'earrings'],
                ['Serene Yoga Leggings', 'Serene', 2199, 'leggings'],
                ['Summit Hiking Backpack', 'Summit', 4999, 'hiking backpack'],
                ['Craft Canvas Apron', 'Craft', 1299, 'canvas apron'],
                ['WoolBlend Overcoat', 'WoolBlend', 7999, 'overcoat'],
                ['AirMesh Running Tee', 'AirMesh', 1499, 'running shirt'],
                ['Pearl Occasion Clutch', 'Pearl', 2499, 'clutch bag'],
                ['Denim Straight Jeans', 'Denim', 2999, 'jeans'],
                ['Festival Embroidered Jacket', 'Festival', 4299, 'embroidered jacket'],
            ],
            'home-living' => [
                ['Halo Air Purifier', 'Halo', 11499, 'air purifier'],
                ['Nest Cotton Bedsheet Set', 'Nest', 2499, 'bedsheet'],
                ['Mira Ceramic Dinner Set', 'Mira', 3999, 'dinnerware'],
                ['Glow Scented Candle Trio', 'Glow', 899, 'scented candle'],
                ['Cloud Memory Foam Pillow', 'Cloud', 1499, 'pillow'],
                ['Oakline Coffee Table', 'Oakline', 8999, 'coffee table'],
                ['Verde Indoor Planter', 'Verde', 1299, 'indoor planter'],
                ['Aura Wall Clock', 'Aura', 1799, 'wall clock'],
                ['Muse Floor Lamp', 'Muse', 5999, 'floor lamp'],
                ['Loom Handwoven Rug', 'Loom', 6999, 'rug'],
                ['PureBath Towel Set', 'PureBath', 1899, 'towels'],
                ['Zen Bamboo Storage Box', 'Zen', 1199, 'storage box'],
                ['Frame Gallery Photo Set', 'Frame', 2499, 'photo frames'],
                ['Comfort Recliner Chair', 'Comfort', 15999, 'recliner chair'],
                ['Stoneware Serving Bowl', 'Stoneware', 999, 'serving bowl'],
                ['Breeze Sheer Curtains', 'Breeze', 2299, 'curtains'],
                ['Calm Essential Oil Diffuser', 'Calm', 2499, 'oil diffuser'],
                ['Marble Soap Dispenser', 'Marble', 799, 'soap dispenser'],
                ['Nordic Bookshelf', 'Nordic', 10999, 'bookshelf'],
                ['FoldAway Study Desk', 'FoldAway', 7499, 'study desk'],
                ['SoftNest Throw Blanket', 'SoftNest', 1799, 'throw blanket'],
                ['Copper Bottle Set', 'Copper', 1299, 'copper bottle'],
                ['Willow Laundry Basket', 'Willow', 1599, 'laundry basket'],
                ['Urban Shoe Rack', 'Urban', 4999, 'shoe rack'],
                ['Casa Kitchen Canisters', 'Casa', 1499, 'kitchen canisters'],
                ['Crystal Wine Glass Set', 'Crystal', 2299, 'wine glasses'],
                ['Haven Mattress Topper', 'Haven', 5499, 'mattress topper'],
                ['Luxe Velvet Cushion Pair', 'Luxe', 1299, 'cushions'],
                ['Terra Wall Art Canvas', 'Terra', 2999, 'wall art'],
                ['Aroma Reed Diffuser', 'Aroma', 999, 'reed diffuser'],
                ['CleanStep Door Mat', 'CleanStep', 699, 'doormat'],
                ['Metro Bar Stool', 'Metro', 3999, 'bar stool'],
                ['FreshGlass Water Jug', 'FreshGlass', 1199, 'glass jug'],
                ['Organize Drawer Dividers', 'Organize', 799, 'drawer organizer'],
                ['Linen Table Runner', 'Linen', 999, 'table runner'],
                ['Pebble Bath Mat', 'Pebble', 1299, 'bath mat'],
                ['Garden Solar Lanterns', 'Garden', 2499, 'solar lantern'],
                ['Oak Serving Tray', 'Oak', 1499, 'serving tray'],
                ['SleepWell Quilt', 'SleepWell', 4299, 'quilt'],
                ['Studio Accent Mirror', 'Studio', 5499, 'accent mirror'],
                ['Bloom Artificial Plant', 'Bloom', 1899, 'artificial plant'],
                ['Elite Cutlery Set', 'Elite', 2499, 'cutlery'],
                ['Harmony Wind Chime', 'Harmony', 899, 'wind chime'],
                ['Slate Coaster Set', 'Slate', 699, 'coasters'],
                ['Nook Side Table', 'Nook', 3999, 'side table'],
                ['Comfy Bean Bag', 'Comfy', 5999, 'bean bag'],
                ['Bright Vanity Mirror', 'Bright', 3299, 'vanity mirror'],
                ['Pantry Glass Jars', 'Pantry', 1699, 'glass jars'],
                ['Aqua Shower Caddy', 'Aqua', 999, 'shower caddy'],
                ['Heritage Brass Diya Set', 'Heritage', 1199, 'brass diya'],
            ],
            'appliances' => [
                ['Pulse Espresso Machine', 'Pulse', 18999, 'espresso machine'],
                ['Nova Front Load Washer', 'Nova', 28999, 'washing machine'],
                ['FrostFree Double Door Refrigerator', 'FrostFree', 42999, 'refrigerator'],
                ['QuickHeat Microwave Oven', 'QuickHeat', 9499, 'microwave oven'],
                ['Breeze Tower Fan', 'Breeze', 4999, 'tower fan'],
                ['SteamPro Garment Steamer', 'SteamPro', 3499, 'garment steamer'],
                ['ChefMate Mixer Grinder', 'ChefMate', 3999, 'mixer grinder'],
                ['ToastMax Sandwich Maker', 'ToastMax', 2199, 'sandwich maker'],
                ['PureWash Dishwasher', 'PureWash', 34999, 'dishwasher'],
                ['CoolAir Split AC 1.5 Ton', 'CoolAir', 37999, 'air conditioner'],
                ['BakeMaster Convection Oven', 'BakeMaster', 12499, 'convection oven'],
                ['CleanJet Vacuum Cleaner', 'CleanJet', 8999, 'vacuum cleaner'],
                ['HydroPlus Water Purifier', 'HydroPlus', 14999, 'water purifier'],
                ['RiceEasy Electric Cooker', 'RiceEasy', 2799, 'rice cooker'],
                ['BlendPro Juicer', 'BlendPro', 5499, 'juicer'],
                ['DrySmart Hair Dryer', 'DrySmart', 1999, 'hair dryer'],
                ['GrillPro Electric Grill', 'GrillPro', 5999, 'electric grill'],
                ['HeatWave Room Heater', 'HeatWave', 3499, 'room heater'],
                ['IronEase Steam Iron', 'IronEase', 2499, 'steam iron'],
                ['FreshBrew Coffee Maker', 'FreshBrew', 6999, 'coffee maker'],
                ['AirCrisp Air Fryer', 'AirCrisp', 8999, 'air fryer'],
                ['ChillMate Wine Cooler', 'ChillMate', 24999, 'wine cooler'],
                ['SpinDry Semi Auto Washer', 'SpinDry', 15999, 'semi automatic washing machine'],
                ['FlamePro Induction Cooktop', 'FlamePro', 2999, 'induction cooktop'],
                ['KettleQuick Electric Kettle', 'KettleQuick', 1499, 'electric kettle'],
                ['DoughMate Stand Mixer', 'DoughMate', 16999, 'stand mixer'],
                ['AquaMist Humidifier', 'AquaMist', 3999, 'humidifier'],
                ['DryBox Food Dehydrator', 'DryBox', 7999, 'food dehydrator'],
                ['Smoothie Personal Blender', 'Smoothie', 2999, 'personal blender'],
                ['ProClean Pressure Washer', 'ProClean', 11999, 'pressure washer'],
                ['Spark Chimney Hood', 'Spark', 17999, 'kitchen chimney'],
                ['SmartScale Kitchen Scale', 'SmartScale', 999, 'kitchen scale'],
                ['CoolCube Mini Fridge', 'CoolCube', 11999, 'mini fridge'],
                ['Whisk Hand Blender', 'Whisk', 2499, 'hand blender'],
                ['FreshSeal Vacuum Sealer', 'FreshSeal', 4999, 'vacuum sealer'],
                ['SteamMop Floor Cleaner', 'SteamMop', 7999, 'steam mop'],
                ['Breakfast Toaster 4 Slice', 'Breakfast', 3499, 'toaster'],
                ['IceFlow Portable Ice Maker', 'IceFlow', 15999, 'ice maker'],
                ['PureDry Clothes Dryer', 'PureDry', 32999, 'clothes dryer'],
                ['NutriCook Soup Maker', 'NutriCook', 6999, 'soup maker'],
                ['FreshAir Exhaust Fan', 'FreshAir', 1999, 'exhaust fan'],
                ['AquaClean Steam Cleaner', 'AquaClean', 9999, 'steam cleaner'],
                ['ChefPro Wet Grinder', 'ChefPro', 6499, 'wet grinder'],
                ['Slimline Built-in Hob', 'Slimline', 21999, 'built in hob'],
                ['CoolMist Air Cooler', 'CoolMist', 10999, 'air cooler'],
                ['BottleSterilizer Pro', 'SterilePro', 4499, 'bottle sterilizer'],
                ['RotiMate Dough Kneader', 'RotiMate', 7999, 'dough kneader'],
                ['LintAway Fabric Shaver', 'LintAway', 999, 'fabric shaver'],
                ['ChefTimer Egg Boiler', 'ChefTimer', 1299, 'egg boiler'],
                ['PowerBlend Food Processor', 'PowerBlend', 11999, 'food processor'],
            ],
        ];

        $badges = ['Best Seller', 'New Arrival', 'Limited Deal', 'Top Rated', 'Festive Pick', 'Customer Favorite', 'Hot Deal', 'Premium Pick'];
        $deliveryEtas = ['Same day dispatch', 'Tomorrow delivery', 'Delivery in 2 days', 'Delivery in 48 hours', 'Delivery by Friday'];

        $sequence = 1001;
        foreach ($catalog as $categorySlug => $items) {
            foreach ($items as [$name, $brand, $basePrice, $imageQuery]) {
                $sku = 'NS-'.$sequence;
                $price = $basePrice + (($sequence % 7) * 150);
                $comparePrice = $price + max(500, (int) round($price * (0.10 + (($sequence % 5) * 0.02))));
                $rating = number_format(4.1 + (($sequence % 9) / 10), 1);
                $badge = $badges[$sequence % count($badges)];
                $deliveryEta = $deliveryEtas[$sequence % count($deliveryEtas)];

            $product = Product::query()->updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $categoryMap[$categorySlug]->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'brand' => $brand,
                    'short_description' => 'Premium '.$name.' designed for everyday shopping.',
                    'description' => $name.' brings dependable performance, polished design, and strong value to a modern ecommerce catalog.',
                    'price' => $price,
                    'compare_price' => $comparePrice,
                    'stock' => 10 + ($sequence % 90),
                    'rating' => $rating,
                    'badge_text' => $badge,
                    'delivery_eta' => $deliveryEta,
                    'search_keywords' => strtolower($brand.' '.$name.' '.$categorySlug.' '.$imageQuery.' premium ecommerce'),
                    'is_featured' => $sequence % 4 === 0,
                    'is_active' => true,
                    'meta_title' => $name.' | Northstar Commerce',
                    'meta_description' => 'Buy '.$name.' from Northstar Commerce.',
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                [
                    'path' => 'https://loremflickr.com/900/900/'.urlencode($imageQuery.',product').'?lock='.$sequence,
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
