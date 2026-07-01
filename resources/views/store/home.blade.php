@extends('layouts.app', ['title' => 'Northstar Commerce | Blade eCommerce'])

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow">Premium audio and lifestyle</div>
            <h1>Shop bold headphones, sleek gadgets, and curated essentials.</h1>
            <p>Northstar delivers a modern storefront experience with fast checkout, smart product discovery, and live launch offers built in Blade.</p>
            <div class="hero-actions">
                <a class="primary-btn" href="{{ route('shop') }}">Shop collection</a>
                <a class="ghost-btn" href="{{ route('architecture') }}">See architecture</a>
            </div>

            <div class="hero-stat-grid">
                <div class="hero-stat">
                    <strong>Free shipping</strong>
                    <span>On all orders</span>
                </div>
                <div class="hero-stat">
                    <strong>Easy returns</strong>
                    <span>30-day guarantee</span>
                </div>
                <div class="hero-stat">
                    <strong>Secure checkout</strong>
                    <span>Trusted payments</span>
                </div>
            </div>
        </div>

        <div class="hero-banner">
            <span class="hero-banner__badge">Launch event</span>
            <h2>Discover premium sound with limited-time offers on headphones and audio gear.</h2>
            <p>Our storefront combines marketplace polish with Laravel Blade performance for a beautiful shopping experience.</p>
            <div class="hero-banner__meta">
                <div class="helper">Sale ends in</div>
                <strong data-countdown="{{ $flashDealEndsAt->toIso8601String() }}">--</strong>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <div>
                <div class="eyebrow">Featured categories</div>
                <h2>Shop by collection</h2>
                <p class="section-subtitle">Explore the top categories driving our best offers.</p>
            </div>
        </div>

        <div class="hero-strip">
            @foreach($categories->take(4) as $category)
                <article class="panel panel-category">
                    <div class="eyebrow">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                    <h3>{{ $category->name }}</h3>
                    <p class="section-subtitle">{{ $category->description }}</p>
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="ghost-btn">Browse</a>
                </article>
            @endforeach
        </div>
    </section>

    @foreach ([
        'Launch Deals' => $featuredProducts,
        'Top Electronics' => $bestElectronics,
        'Trending Now' => $trendingProducts,
        'New Arrivals' => $latestProducts,
    ] as $label => $items)
        <section class="section">
            <div class="section-head">
                <div>
                    <div class="eyebrow">{{ $label }}</div>
                    <h2>{{ $label }}</h2>
                    <div class="section-subtitle">Curated product picks, live markdowns, and best-selling accessories.</div>
                </div>
                <a href="{{ route('shop') }}" class="ghost-btn">View all</a>
            </div>

            <div class="card-grid">
                @foreach($items as $product)
                    <article class="product-card">
                        @if($product->discount_percent)
                            <span class="discount-chip">{{ $product->discount_percent }}% off</span>
                        @endif
                        <div class="product-media">
                            <img class="product-image" src="{{ $product->primary_image }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="product-body">
                            <span class="badge">{{ $product->badge_text ?: ($product->brand ?? 'Northstar Select') }}</span>
                            <h3 class="product-title" style="margin-top:12px;">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="product-meta">{{ $product->short_description }}</p>
                            <div class="price">
                                <span>Rs. {{ number_format($product->price, 2) }}</span>
                                @if($product->compare_price)
                                    <span class="price-old">Rs. {{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="product-meta">Rating {{ number_format($product->rating, 1) }}/5</div>
                            <div class="product-card__cta">
                                <a href="{{ route('products.show', $product) }}" class="primary-btn">View</a>
                                <form action="{{ route('cart.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="ghost-btn">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
@endsection
