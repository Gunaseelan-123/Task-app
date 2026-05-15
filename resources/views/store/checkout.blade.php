@extends('layouts.app', ['title' => 'Checkout | Northstar'])

@section('content')
    <div class="checkout-grid">
        <section class="table-card">
            <div class="eyebrow">Checkout</div>
            <h2>Delivery and payment</h2>
            <form action="{{ route('checkout.place-order') }}" method="post" class="stack">
                @csrf
                <label>
                    <span class="label">Choose address</span>
                    <select class="field" name="address_id">
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}">{{ $address->full_name }} | {{ $address->city }} | {{ $address->postal_code }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="label">Payment method</span>
                    <select class="field" name="payment_method">
                        <option value="cod">Cash on delivery</option>
                        <option value="card">Credit or debit card</option>
                        <option value="upi">UPI</option>
                    </select>
                </label>
                <label>
                    <span class="label">Coupon</span>
                    <input class="field" type="text" name="coupon_code" placeholder="SAVE10">
                </label>
                <label>
                    <span class="label">Order notes</span>
                    <textarea class="field" name="notes" rows="4" placeholder="Delivery preferences, landmark, or timing"></textarea>
                </label>
                <button type="submit" class="primary-btn">Place order</button>
            </form>
        </section>

        <section class="table-card">
            <div class="eyebrow">Add address</div>
            <h2>Saved addresses</h2>
            <form action="{{ route('checkout.address.store') }}" method="post" class="stack">
                @csrf
                <div class="field-row">
                    <input class="field" type="text" name="full_name" placeholder="Full name">
                    <input class="field" type="text" name="phone" placeholder="Phone">
                </div>
                <div class="field-row">
                    <select class="field" name="type">
                        <option value="home">Home</option>
                        <option value="office">Office</option>
                        <option value="other">Other</option>
                    </select>
                    <input class="field" type="text" name="postal_code" placeholder="Postal code">
                </div>
                <input class="field" type="text" name="line_1" placeholder="Address line 1">
                <input class="field" type="text" name="line_2" placeholder="Address line 2">
                <div class="field-row">
                    <input class="field" type="text" name="city" placeholder="City">
                    <input class="field" type="text" name="state" placeholder="State">
                </div>
                <input class="field" type="text" name="country" placeholder="Country" value="India">
                <button class="ghost-btn" type="submit">Save address</button>
            </form>
        </section>

        <aside class="table-card">
            <div class="eyebrow">Summary</div>
            <h2>Payable today</h2>
            <div class="stack">
                <div class="field-row"><span>Subtotal</span><strong>Rs. {{ number_format($summary['subtotal'], 2) }}</strong></div>
                <div class="field-row"><span>Shipping</span><strong>Rs. {{ number_format($summary['shipping'], 2) }}</strong></div>
                <div class="field-row"><span>Tax</span><strong>Rs. {{ number_format($summary['tax'], 2) }}</strong></div>
                <div class="field-row"><span>Total</span><strong>Rs. {{ number_format($summary['grand_total'], 2) }}</strong></div>
            </div>
        </aside>
    </div>
@endsection
