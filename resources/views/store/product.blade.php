@extends('layouts.app', ['title' => $product->name.' | Northstar', 'metaDescription' => $product->meta_description ?: $product->short_description])

@section('content')
    <section class="product-page">
        <div class="product-hero">
            <article class="product-hero-card product-gallery-card">
                <div class="badge-pill">{{ $product->badge_text ?: ($product->brand ?? 'Northstar Select') }}</div>
                <div class="product-gallery-main">
                    <img id="primary-product-image" src="{{ $product->primary_image }}" alt="{{ $product->name }}">
                </div>
                <div class="product-gallery-thumbs">
                    @forelse($product->images as $image)
                        <button type="button" class="thumb-button" data-gallery-thumb data-gallery-target="#primary-product-image" data-full-src="{{ $image->full_path ?? $image->path }}">
                            <img src="{{ $image->path }}" alt="{{ $image->alt_text ?? $product->name }}">
                        </button>
                    @empty
                        <div class="thumb-placeholder">
                            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" data-full-src="{{ $product->primary_image }}">
                        </div>
                    @endforelse
                </div>
            </article>

            <aside class="product-summary-card">
                <div class="product-summary-header">
                    <h1>{{ $product->name }}</h1>
                    <p class="product-intro-text">{{ $product->short_description }}</p>
                </div>

                <div class="product-price-row">
                    <div>
                        <span class="product-price">Rs. {{ number_format($product->price, 2) }}</span>
                        @if($product->compare_price)
                            <span class="product-price-old">Rs. {{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>
                    <span class="product-stock">In stock: {{ $product->stock }} units</span>
                </div>

                <div class="product-meta-row">
                    @if($product->reviews->count() > 0)
                        <span class="rating-pill">★ {{ number_format($product->rating, 1) }}</span>
                        <span class="review-count">{{ $product->reviews->count() }} review{{ $product->reviews->count() === 1 ? '' : 's' }}</span>
                    @else
                        <span class="review-count">No reviews yet</span>
                    @endif
                </div>

                <div class="product-actions-card">
                    <form action="{{ route('cart.store') }}" method="post" class="stack">
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

                    <form action="{{ route('wishlist.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="ghost-btn">Add to wishlist</button>
                    </form>
                </div>

                <div class="delivery-card">
                    <div class="eyebrow">Delivery check</div>
                    <h3>Check your pincode</h3>
                    <div class="field-row">
                        <input id="pincode-input" class="field" type="text" placeholder="Enter pincode">
                        <button type="button" class="primary-btn" data-pincode-check data-input="#pincode-input" data-target="#delivery-feedback">Check</button>
                    </div>
                    <p id="delivery-feedback" class="helper">Enter a pincode to see delivery promise.</p>
                </div>
            </aside>
        </div>

        <section class="product-details-grid">
            <article class="panel">
                <strong>Product overview</strong>
                <p class="section-subtitle">{{ $product->description }}</p>
            </article>

            <article class="panel">
                <strong>Delivery promise</strong>
                <p class="section-subtitle">{{ $product->delivery_eta ?: 'Fast fulfillment with tracked shipping.' }}</p>
            </article>

            <article class="panel">
                <strong>Customer rating</strong>
                @if($product->reviews->count() > 0)
                    <p class="section-subtitle">{{ number_format($product->rating, 1) }}/5 average from {{ $product->reviews->count() }} review{{ $product->reviews->count() === 1 ? '' : 's' }}.</p>
                @else
                    <p class="section-subtitle">No reviews yet — be the first to rate this product.</p>
                @endif
            </article>
        </section>

        <section class="reviews-section">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Ratings and reviews</div>
                    <h2>What customers say</h2>
                </div>
            </div>

            @auth
                <form action="{{ route('products.reviews.store', $product) }}" method="post" class="review-form panel">
                    @csrf

                    @if($errors->any())
                        <div class="flash flash--error" style="margin-bottom:16px;">
                            <ul style="margin:0;padding-left:18px;">
                                @foreach($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="field-row review-rate-row">
                        <label class="form-group">
                            <span class="label">Rating</span>
                            <select name="rating" class="field" required>
                                <option value="">Choose a rating</option>
                                @foreach(range(5, 1) as $star)
                                    <option value="{{ $star }}" @selected(old('rating') == $star)>{{ $star }} star{{ $star === 1 ? '' : 's' }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <label class="form-group">
                        <span class="label">Title</span>
                        <input class="field" type="text" name="title" maxlength="120" value="{{ old('title') }}" placeholder="Short summary of your experience">
                    </label>

                    <label class="form-group">
                        <span class="label">Review</span>
                        <textarea class="field" name="body" rows="4" placeholder="Tell other shoppers what you liked or what could improve">{{ old('body') }}</textarea>
                    </label>

                    <button class="primary-btn" type="submit">Submit review</button>
                </form>
            @else
                <article class="review-card">
                    <strong>Share your experience</strong>
                    <p class="helper">Please <a href="{{ route('login') }}">log in</a> to post a review.</p>
                </article>
            @endauth

            <div class="review-list">
                @forelse($product->reviews as $review)
                    <article class="review-card">
                        <strong>{{ $review->title ?: 'Verified customer review' }}</strong>
                        <div class="helper review-meta">
                            <span aria-label="{{ $review->rating }} out of 5 stars" class="rating-pill">{{ $review->star_label }}</span>
                            <span>{{ $review->user?->name ?? 'Customer' }}</span>
                        </div>
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

        <section class="related-section">
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
    </section>
@endsection
