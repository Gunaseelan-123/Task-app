@extends('layouts.app', ['title' => 'Northstar Commerce | Blade eCommerce'])

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Marketplace-grade UX</div>
            <h1>Buy premium essentials with the speed of a big marketplace.</h1>
            <p>Northstar pairs Laravel Blade with secure auth, smart search, flash deals, sticky commerce flows, and an admin stack ready for real operations.</p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:18px;">
                <a class="primary-btn" href="{{ route('shop') }}">Shop now</a>
                <a class="ghost-btn" href="{{ route('architecture') }}">View architecture</a>
            </div>
        </div>

        <div class="hero-banner">
            <span class="hero-banner__badge">Flash deal live now</span>
            <h2 style="font-size:2.4rem;line-height:1.05;margin:18px 0 10px;">Flagship electronics, fashion picks, and same-day delivery promises.</h2>
            <p style="max-width:520px;color:rgba(255,255,255,0.78);">This storefront is built entirely inside Laravel with Blade views, JS interactions, and secure backend flows.</p>
            <div class="hero-banner__meta">
                <div class="helper" style="color:rgba(255,255,255,0.68);">Deal refreshes in</div>
                <strong data-countdown="{{ $flashDealEndsAt->toIso8601String() }}" style="display:block;font-size:1.6rem;margin-top:8px;">--</strong>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="hero-strip">
            @foreach($categories->take(4) as $category)
                <article class="panel" style="padding:20px;">
                    <div class="eyebrow">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                    <h3>{{ $category->name }}</h3>
                    <p class="section-subtitle">{{ $category->description }}</p>
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="ghost-btn">Explore</a>
                </article>
            @endforeach
        </div>
    </section>

    @foreach ([
        'Flash Deals' => $featuredProducts,
        'Best of Electronics' => $bestElectronics,
        'Trending Now' => $trendingProducts,
        'Fresh Arrivals' => $latestProducts,
    ] as $label => $items)
        <section class="section">
            <div class="section-head">
                <div>
                    <div class="eyebrow">{{ $label }}</div>
                    <h2>{{ $label }}</h2>
                    <div class="section-subtitle">Premium product cards, hover CTAs, strike pricing, and rating-led browsing.</div>
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
