<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request): View
    {
        $cart = $this->cartService->resolve($request)->load('items.product.images', 'items.product.variants');
        $summary = $this->cartService->summary($cart);

        return view('store.cart', compact('cart', 'summary'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        $variant = isset($data['variant_id']) ? ProductVariant::query()->findOrFail($data['variant_id']) : null;

        $this->cartService->add($request, $product, $data['quantity'], $variant);

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateQuantity($item, $data['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
    