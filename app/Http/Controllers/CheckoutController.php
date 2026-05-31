<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
    ) {
    }

    public function index(Request $request): View
    {
        $cart = $this->cartService->resolve($request)->load('items.product.images');
        $summary = $this->cartService->summary($cart);
        $addresses = $request->user()->addresses()->latest()->get();

        return view('store.checkout', compact('cart', 'summary', 'addresses'));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:home,office,other'],
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'line_1' => ['required', 'string', 'max:255'],
            'line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $request->user()->addresses()->create([
            ...$data,
            'is_default' => (bool) ($data['is_default'] ?? false),
        ]);

        return back()->with('success', 'Address saved.');
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'address_id' => ['required', 'exists:addresses,id'],
            'payment_method' => ['required', 'in:'.implode(',', Order::allowedPaymentMethods())],
            'coupon_code' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = $this->cartService->resolve($request)->load('items.product');
        $address = Address::query()->where('user_id', $request->user()->id)->findOrFail($data['address_id']);
        $coupon = filled($data['coupon_code'] ?? null)
            ? Coupon::query()->where('code', $data['coupon_code'])->first()
            : null;

        $order = $this->checkoutService->placeOrder($cart, $address, $data['payment_method'], $coupon, $data['notes'] ?? null);

        return redirect()->route('account.dashboard')->with('success', 'Order placed successfully. Tracking: '.$order->tracking_number);
    }
}
