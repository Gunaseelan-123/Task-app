<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function __construct(
        private readonly CatalogService $catalog,
        private readonly CartService $cartService,
    )
    {
    }

    public function home()
    {
        return view('store.home', $this->catalog->homeData());
    }

    public function shop(Request $request)
    {
        $filters = $request->only(['search', 'category', 'min_price', 'max_price', 'rating', 'sort']);

        return view('store.shop', $this->catalog->shopData($filters) + ['filters' => $filters]);
    }

    public function product(Product $product)
    {
        $product->load(['category', 'images', 'variants', 'reviews.user']);

        $relatedProducts = Product::query()
            ->with('images')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->take(4)
            ->get();

        $cart = $this->cartService->resolve(request())->loadCount('items');

        return view('store.product', compact('product', 'relatedProducts', 'cart'));
    }

    public function submitReview(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string'],
        ]);

        $product->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'is_verified_purchase' => true,
        ]);

        $product->rating = round((float) $product->reviews()->avg('rating'), 1);
        $product->save();

        return redirect()->route('products.show', $product)
            ->with('success', 'Thanks for your review! Your rating has been added.');
    }

    public function searchSuggestions(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        return response()->json(
            Product::query()
                ->where('is_active', true)
                ->where(function ($query) use ($request): void {
                    $query
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->orWhere('brand', 'like', '%'.$request->q.'%')
                        ->orWhere('search_keywords', 'like', '%'.$request->q.'%');
                })
                ->take(6)
                ->get(['name', 'slug', 'price'])
        );
    }

    public function architecture()
    {
        return view('docs.architecture', [
            'databaseTables' => [
                'users',
                'otp_challenges',
                'login_alerts',
                'products',
                'categories',
                'carts',
                'orders',
                'order_items',
                'reviews',
                'wishlists',
                'addresses',
                'coupons',
            ],
            'adminModules' => ['Dashboard', 'Products', 'Categories', 'Orders', 'Banners', 'Coupons'],
            'frontendStack' => ['Laravel Blade', 'Vite', 'Axios', 'CSS'],
        ]);
    }
}
