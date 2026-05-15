@extends('layouts.app', ['title' => 'Cart | Northstar'])

@section('content')
    <div class="checkout-grid">
        <section class="table-card">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Your cart</div>
                    <h2>{{ $cart->items->count() }} items ready</h2>
                </div>
            </div>

            @forelse($cart->items as $item)
                <article class="panel" style="padding:18px;margin-bottom:14px;">
                    <div class="field-row">
                        <div>
                            <strong>{{ $item->product?->name }}</strong>
                            <p class="helper">{{ $item->product?->short_description }}</p>
                            <div class="price"><span>Rs. {{ number_format($item->unit_price, 2) }}</span></div>
                        </div>
                        <div class="stack">
                            <form action="{{ route('cart.update', $item) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <label class="label">Quantity</label>
                                <input class="field" type="number" min="0" name="quantity" value="{{ $item->quantity }}">
                                <button class="primary-btn" type="submit" style="margin-top:10px;">Update</button>
                            </form>
                            <form action="{{ route('cart.destroy', $item) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="ghost-btn" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">Your cart is empty.</div>
            @endforelse
        </section>

        <aside class="table-card">
            <div class="eyebrow">Order summary</div>
            <h2>Checkout snapshot</h2>
            <div class="stack">
                <div class="field-row"><span>Subtotal</span><strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong></div>
                <div class="field-row"><span>Discount</span><strong>Rs. {{ number_format($summary['discount'], 2) }}</strong></div>
                <div class="field-row"><span>Shipping</span><strong>Rs. {{ number_format($summary['shipping'], 2) }}</strong></div>
                <div class="field-row"><span>Tax</span><strong>Rs. {{ number_format($summary['tax'], 2) }}</strong></div>
                <div class="field-row"><span>Total</span><strong>Rs. {{ number_format($summary['grand_total'], 2) }}</strong></div>
            </div>
            @auth
                <a href="{{ route('checkout.index') }}" class="primary-btn" style="display:block;text-align:center;margin-top:18px;">Proceed to checkout</a>
            @else
                <a href="{{ route('login') }}" class="primary-btn" style="display:block;text-align:center;margin-top:18px;">Login to checkout</a>
            @endauth
        </aside>
    </div>
@endsection
