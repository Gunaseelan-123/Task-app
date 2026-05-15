<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartService
{
    public function resolve(Request $request): Cart
    {
        $attributes = $request->user()
            ? ['user_id' => $request->user()->id]
            : ['session_id' => $request->session()->getId()];

        return Cart::query()->firstOrCreate($attributes);
    }

    public function add(Request $request, Product $product, int $quantity = 1, ?ProductVariant $variant = null): Cart
    {
        $cart = $this->resolve($request);

        CartItem::query()->updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
            ],
            [
                'quantity' => $quantity,
                'unit_price' => $variant?->price ?: $product->price,
            ]
        );

        return $cart->fresh('items.product.images', 'items.product.variants');
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $item->delete();
            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function summary(Cart $cart, float $discount = 0): array
    {
        $subtotal = (float) $cart->items->sum(fn (CartItem $item) => $item->quantity * $item->unit_price);
        $shipping = $subtotal >= 1500 ? 0.0 : 79.0;
        $tax = round(max($subtotal - $discount, 0) * 0.18, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'tax' => $tax,
            'grand_total' => max($subtotal - $discount, 0) + $shipping + $tax,
        ];
    }
}
