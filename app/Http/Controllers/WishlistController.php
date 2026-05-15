<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->wishlistItems()->with('product.images')->latest()->get();

        return view('store.wishlist', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        WishlistItem::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
        ]);

        return back()->with('success', 'Saved to wishlist.');
    }

    public function destroy(WishlistItem $wishlist): RedirectResponse
    {
        $wishlist->delete();

        return back()->with('success', 'Removed from wishlist.');
    }
}
