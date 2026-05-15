<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class CatalogService
{
    public function homeData(): array
    {
        return [
            'banners' => Banner::query()->where('is_active', true)->latest()->take(3)->get(),
            'flashDealEndsAt' => now()->addHours(11)->endOfHour(),
            'featuredProducts' => Product::query()->with('images')->where('is_featured', true)->where('is_active', true)->take(8)->get(),
            'trendingProducts' => Product::query()->with('images')->where('is_active', true)->orderByDesc('rating')->take(8)->get(),
            'latestProducts' => Product::query()->with('images')->where('is_active', true)->latest()->take(8)->get(),
            'bestElectronics' => Product::query()->with('images')->where('is_active', true)->whereHas('category', fn ($query) => $query->where('slug', 'electronics'))->take(8)->get(),
            'categories' => Category::query()->where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get(),
        ];
    }

    public function shopData(array $filters): array
    {
        $query = Product::query()->with(['category', 'images'])->where('is_active', true);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        if (!empty($filters['category'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category']));
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['rating'])) {
            $query->where('rating', '>=', $filters['rating']);
        }

        match ($filters['sort'] ?? 'latest') {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        return [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'priceRange' => [
                'min' => (float) Product::query()->min('price'),
                'max' => (float) Product::query()->max('price'),
            ],
        ];
    }
}
