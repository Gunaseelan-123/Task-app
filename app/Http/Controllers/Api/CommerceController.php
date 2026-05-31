<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\WishlistItem;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommerceController extends Controller
{
    public function cart(Request $request): JsonResponse
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $cart->load('items.product.images');

        return response()->json($cart);
    }

    public function addToCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $cart = Cart::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $product = Product::query()->findOrFail($data['product_id']);

        $item = CartItem::query()->updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $data['product_variant_id'] ?? null,
            ],
            [
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
            ]
        );

        return response()->json([
            'message' => 'Added to cart.',
            'item' => $item,
        ], 201);
    }

    public function wishlist(Request $request): JsonResponse
    {
        return response()->json(
            WishlistItem::query()
                ->with('product.images')
                ->where('user_id', $request->user()->id)
                ->get()
        );
    }

    public function addToWishlist(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $wishlist = WishlistItem::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
        ]);

        return response()->json($wishlist, 201);
    }

    public function addresses(Request $request): JsonResponse
    {
        return response()->json($request->user()->addresses()->latest()->get());
    }

    public function createAddress(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:home,office,other'],
            'full_name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'line_1' => ['required', 'string'],
            'line_2' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'country' => ['required', 'string'],
        ]);

        return response()->json($request->user()->addresses()->create($data), 201);
    }

    public function placeOrder(Request $request, CheckoutService $checkoutService): JsonResponse
    {
        $data = $request->validate([
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'payment_method' => ['required', 'in:'.implode(',', Order::allowedPaymentMethods())],
        ]);

        $cart = Cart::query()->with('items.product')->where('user_id', $request->user()->id)->firstOrFail();
        $address = Address::query()->where('user_id', $request->user()->id)->findOrFail($data['address_id']);

        return response()->json(
            $checkoutService->placeOrder($cart, $address, $data['payment_method'])
        );
    }

    public function orders(Request $request): JsonResponse
    {
        return response()->json($request->user()->orders()->with('items')->latest()->get());
    }

    public function addReview(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string'],
        ]);

        $review = Review::query()->create([
            ...$data,
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'is_verified_purchase' => true,
        ]);

        $product->rating = round((float) $product->reviews()->avg('rating'), 1);
        $product->save();

        return response()->json($review, 201);
    }
}
