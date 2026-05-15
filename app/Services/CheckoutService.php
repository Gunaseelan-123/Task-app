<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function placeOrder(Cart $cart, Address $address, string $paymentMethod, ?Coupon $coupon = null, ?string $notes = null): Order
    {
        $cart->loadMissing('items.product');

        $subtotal = (float) $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price);
        $discount = $coupon && $coupon->isValidFor($subtotal)
            ? $this->calculateDiscount($coupon, $subtotal)
            : 0.0;
        $shipping = $subtotal >= 1500 ? 0.0 : 79.0;
        $tax = round(max($subtotal - $discount, 0) * 0.18, 2);
        $grandTotal = max($subtotal - $discount, 0) + $shipping + $tax;

        return DB::transaction(function () use ($cart, $address, $paymentMethod, $subtotal, $discount, $shipping, $tax, $grandTotal, $coupon, $notes): Order {
            $order = Order::query()->create([
                'user_id' => $cart->user_id,
                'address_id' => $address->id,
                'order_number' => 'NS-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'status' => 'placed',
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'paid',
                'payment_method' => $paymentMethod,
                'tracking_number' => 'TRK'.Str::upper(Str::random(10)),
                'notes' => $notes,
                'coupon_code' => $coupon?->code,
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'shipping_total' => $shipping,
                'tax_total' => $tax,
                'grand_total' => $grandTotal,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'sku' => $item->product?->sku,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->unit_price * $item->quantity,
                ]);

                if ($item->product) {
                    $item->product->decrement('stock', min($item->quantity, $item->product->stock));
                }
            }

            $cart->items()->delete();

            return $order->load('items');
        });
    }

    private function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($coupon->type === 'percentage') {
            return round($subtotal * ($coupon->value / 100), 2);
        }

        return min($subtotal, (float) $coupon->value);
    }
}
