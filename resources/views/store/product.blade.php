@extends('layouts.app', ['title' => $product->name.' | Northstar', 'metaDescription' => $product->meta_description ?: $product->short_description])

@section('content')
    <section class="product-layout">
        <div class="product-main">
            <div class="product-gallery">
                <div class="main-image">
                    <img id="primary-product-image" src="{{ $product->primary_image }}" alt="{{ $product->name }}">
                </div>
                <div class="thumbs">
                    @forelse($product->images as $image)
                        <img src="{{ $image->path }}" alt="{{ $image->alt_text ?? $product->name }}" data-gallery-thumb data-gallery-target="#primary-product-image">
                    @empty
                        <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" data-gallery-thumb data-gallery-target="#primary-product-image">
                    @endforelse
                </div>
            </div>

            <section class="section">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">Product overview</div>
                        <h2>Highlights and description</h2>
                    </div>
                </div>
                <div class="info-grid">
                    <article class="panel" style="padding:20px;">
                        <strong>Premium build</strong>
                        <p class="section-subtitle">{{ $product->description }}</p>
                    </article>
                    <article class="panel" style="padding:20px;">
                        <strong>Delivery promise</strong>
                        <p class="section-subtitle">{{ $product->delivery_eta ?: 'Fast fulfillment with tracked shipping.' }}</p>
                    </article>
                    <article class="panel" style="padding:20px;">
                        <strong>Customer rating</strong>
                        <p class="section-subtitle">{{ number_format($product->rating, 1) }}/5 average rating from verified reviews.</p>
                    </article>
                </div>
            </section>

            <section class="section">
                <div class="eyebrow">Ratings and reviews</div>
                <h2>What customers say</h2>
                <div class="review-list">
                    @forelse($product->reviews as $review)
                        <article class="review-card">
                            <strong>{{ $review->title ?: 'Verified customer review' }}</strong>
                            <div class="helper">{{ $review->user?->name ?? 'Customer' }} | {{ $review->rating }}/5</div>
                            <p>{{ $review->body }}</p>
                        </article>
                    @empty
                        <article class="review-card">
                            <strong>No reviews yet</strong>
                            <p class="helper">The review system is ready for verified purchases and moderation workflows.</p>
                        </article>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="product-aside">
            <span class="badge">{{ $product->badge_text ?: ($product->brand ?? 'Northstar Select') }}</span>
            <h1 style="font-size:2rem;line-height:1.08;margin:14px 0;">{{ $product->name }}</h1>
            <p class="muted">{{ $product->short_description }}</p>
            <div class="price" style="font-size:1.4rem;">
                <span>Rs. {{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="price-old">Rs. {{ number_format($product->compare_price, 2) }}</span>
                @endif
            </div>
            <div class="helper">In stock: {{ $product->stock }} units | {{ number_format($product->rating, 1) }}/5 rating</div>

            <form action="{{ route('cart.store') }}" method="post" class="stack" style="margin-top:18px;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label>
                    <span class="label">Variant</span>
                    <select class="field" name="variant_id">
                        <option value="">Select an option</option>
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->size ?: 'Standard' }} / {{ $variant->color ?: 'Default' }} / Stock {{ $variant->stock }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="label">Quantity</span>
                    <input class="field" type="number" name="quantity" min="1" value="1">
                </label>
                <button class="primary-btn" type="submit">Add to cart</button>
            </form>

            <form action="{{ route('wishlist.store') }}" method="post" style="margin-top:12px;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="ghost-btn" style="width:100%;">Add to wishlist</button>
            </form>

            <div class="section" style="padding-left:0;padding-right:0;">
                <div class="eyebrow">Delivery check</div>
                <h3>Check your pincode</h3>
                <div class="field-row">
                    <input id="pincode-input" class="field" type="text" placeholder="Enter pincode">
                    <button type="button" class="primary-btn" data-pincode-check data-input="#pincode-input" data-target="#delivery-feedback">Check</button>
                </div>
                <p id="delivery-feedback" class="helper">Enter a pincode to see delivery promise.</p>
            </div>
        </aside>
    </section>

    <section class="section">
        <div class="section-head">
            <div>
                <div class="eyebrow">Related products</div>
                <h2>You may also like</h2>
            </div>
        </div>
        <div class="card-grid">
            @foreach($relatedProducts as $related)
                <article class="product-card">
                    <div class="product-media">
                        <img class="product-image" src="{{ $related->primary_image }}" alt="{{ $related->name }}">
                    </div>
                    <div class="product-body">
                        <h3 class="product-title"><a href="{{ route('products.show', $related) }}">{{ $related->name }}</a></h3>
                        <div class="price"><span>Rs. {{ number_format($related->price, 2) }}</span></div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
