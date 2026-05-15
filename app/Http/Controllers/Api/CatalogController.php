<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): JsonResponse
    {
        return response()->json([
            'hero_banners' => Banner::query()->where('is_active', true)->latest()->take(4)->get(),
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->take(8)->get(),
            'flash_deals' => Product::query()->with('images')->where('is_featured', true)->where('is_active', true)->take(6)->get(),
            'best_of_electronics' => Product::query()->with('images')->where('is_active', true)->where('brand', 'Northstar')->take(8)->get(),
            'trending' => Product::query()->with('images')->where('is_active', true)->orderByDesc('rating')->take(8)->get(),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:120'],
            'min_price' => ['nullable', 'numeric'],
            'max_price' => ['nullable', 'numeric'],
            'rating' => ['nullable', 'numeric'],
            'sort' => ['nullable', 'in:latest,price_asc,price_desc,rating'],
        ]);

        $query = Product::query()->with(['category', 'images', 'variants'])->where('is_active', true);

        if (!empty($data['search'])) {
            $query->where('name', 'like', '%'.$data['search'].'%');
        }

        if (!empty($data['category'])) {
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $data['category']));
        }

        if (!empty($data['min_price'])) {
            $query->where('price', '>=', $data['min_price']);
        }

        if (!empty($data['max_price'])) {
            $query->where('price', '<=', $data['max_price']);
        }

        if (!empty($data['rating'])) {
            $query->where('rating', '>=', $data['rating']);
        }

        match ($data['sort'] ?? 'latest') {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        return response()->json($query->paginate(12));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $results = Product::query()
            ->where('is_active', true)
            ->where('name', 'like', '%'.$data['q'].'%')
            ->take(6)
            ->get(['name', 'slug', 'price']);

        return response()->json($results);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'images', 'variants', 'reviews.user']);

        return response()->json([
            'product' => $product,
            'related_products' => Product::query()
                ->with('images')
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(4)
                ->get(),
        ]);
    }
}
