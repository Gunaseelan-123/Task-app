@extends('layouts.app', ['title' => 'Shop | Northstar'])

@section('content')
        <div class="hero-links">
            @foreach($categories->take(4) as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <section class="hero">
            <div class="hero-copy">
                <div class="hero-copy-top">
                    <span class="hero-eyebrow">Premium audio and lifestyle</span>
                    <div class="hero-chip-list">
                        <span class="hero-chip">Fast delivery</span>
                        <span class="hero-chip">Verified reviews</span>
                        <span class="hero-chip">Local support</span>
                    </div>
                </div>

                <h1>Shop bold headphones, sleek gadgets, and curated essentials.</h1>
                <p class="hero-copy__text">Northstar delivers a modern storefront experience with fast checkout, smart product discovery, and live launch offers built in Blade. Everything is optimized for performance, conversions, and trust.</p>

                <div class="hero-actions">
                    <a class="primary-btn" href="{{ route('shop') }}">Shop collection</a>
                    <a class="ghost-btn" href="{{ route('shop', ['sort' => 'rating']) }}">See architecture</a>
                </div>

                <div class="hero-stat-grid">
                    <div class="stat-card">
                        <strong>Free shipping</strong>
                        <span>On all orders over ₹2,500</span>
                    </div>
                    <div class="stat-card">
                        <strong>30-day returns</strong>
                        <span>Easy, no-hassle policy</span>
                    </div>
                    <div class="stat-card">
                        <strong>Secure checkout</strong>
                        <span>Trusted payments across India</span>
                    </div>
                </div>
            </div>

            <div class="hero-panel">
                <div class="hero-panel-pill">Launch event</div>
                <div class="hero-panel-copy">
                    <h2>Discover premium sound with limited-time offers on headphones and audio gear.</h2>
                    <p>Our storefront combines marketplace polish with Laravel Blade performance for a beautiful shopping experience.</p>
                </div>
                <ul class="hero-panel-list">
                    <li>Curated product drops from trusted brands</li>
                    <li>Exclusive launch pricing and bundle savings</li>
                    <li>Fast fulfillment with Chennai-area delivery</li>
                </ul>
                <div class="hero-panel-countdown">
                    <span>Sale ends in 11h 14m 11s</span>
                </div>
            </div>
        </section>

        <section class="home-highlights">
            <article class="feature-card">
                <strong>Curated Collections</strong>
                <p>Each category is handpicked for quality, value, and trend relevance so customers find the best products quickly.</p>
            </article>
            <article class="feature-card">
                <strong>Smarter browsing</strong>
                <p>Search, filters, and product discovery are built for fast results and a polished shopping journey.</p>
            </article>
            <article class="feature-card">
                <strong>Local support</strong>
                <p>Chennai-based order support, easy returns, and fast delivery details to keep trust high.</p>
            </article>
        </section>

    <div class="shop-layout">
        <aside class="filters product-aside">
            <div class="eyebrow">Filter products</div>
            <h2>Refine your catalog</h2>
            <form method="get" action="{{ route('shop') }}" class="stack">
                <input class="field" type="text" name="search" placeholder="Search products" value="{{ $filters['search'] ?? '' }}">
                <select class="field" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="field-row">
                    <input class="field" type="number" name="min_price" placeholder="Min" value="{{ $filters['min_price'] ?? $priceRange['min'] }}">
                    <input class="field" type="number" name="max_price" placeholder="Max" value="{{ $filters['max_price'] ?? $priceRange['max'] }}">
                </div>
                <select class="field" name="rating">
                    <option value="">Any rating</option>
                    @foreach([4.5, 4, 3] as $rating)
                        <option value="{{ $rating }}" @selected((string) ($filters['rating'] ?? '') === (string) $rating)>{{ $rating }} and above</option>
                    @endforeach
                </select>
                <select class="field" name="sort">
                    <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Latest</option>
                    <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price low to high</option>
                    <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price high to low</option>
                    <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Top rated</option>
                </select>
                <button class="primary-btn" type="submit">Apply filters</button>
            </form>
        </aside>

        <section>
            <div class="section-head">
                <div>
                    <div class="eyebrow">Curated catalog</div>
                    <h2>{{ $products->total() }} products found</h2>
                    <p class="section-subtitle">Smart filtering by category, price, rating, and sort order.</p>
                    <p class="helper" style="margin-top:6px; font-size:13px; color:#6B7280;">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                    </p>
                </div>
            </div>

            <div class="card-grid">
                @forelse($products as $product)
                    <article class="product-card">
                        @if($product->discount_percent)
                            <span class="discount-chip">{{ $product->discount_percent }}% off</span>
                        @endif
                        <div class="product-media">
                            <img class="product-image" src="{{ $product->primary_image }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="product-body">
                            <span class="badge">{{ $product->category?->name ?? 'Catalog' }}</span>
                            <h3 class="product-title" style="margin-top:12px;">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="product-rating" style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                                @if($product->rating)
                                    <span style="color:#f59e0b;font-weight:700;">★ {{ number_format($product->rating, 1) }}</span>
                                @endif
                                <span class="helper">{{ $product->reviews()->count() }} Reviews</span>
                            </div>
                            <p class="product-meta">{{ $product->short_description }}</p>
                            <div class="price">
                                <span>Rs. {{ number_format($product->price, 2) }}</span>
                                @if($product->compare_price)
                                    <span class="price-old">Rs. {{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="product-card__cta">
                                <a href="{{ route('products.show', $product) }}" class="primary-btn">Details</a>
                                <form action="{{ route('wishlist.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="ghost-btn">Wishlist</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="panel empty-state">No products match the selected filters.</div>
                @endforelse
            </div>

            @if($products->hasPages())
                @php
                    $qp = request()->except('page');
                    $paginator = $products->appends($qp);
                    $start = max(1, $paginator->currentPage() - 2);
                    $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                @endphp

                <nav class="pagination-container" aria-label="Product pagination">
                    @php
                        $qp = request()->except('page');
                    @endphp
                    <ul class="pagination-list">
                        @php $prev = max(1, $paginator->currentPage() - 1); @endphp
                        <li class="pagination-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                            @if($paginator->onFirstPage())
                                <span class="pagination-link">« Previous</span>
                            @else
                                <a class="pagination-link" href="{{ url()->current() . '?' . http_build_query(array_merge($qp, ['page' => $prev])) }}">« Previous</a>
                            @endif
                        </li>

                        @if($start > 1)
                            <li class="pagination-item">
                                <a class="pagination-link" href="{{ url()->current() . '?' . http_build_query(array_merge($qp, ['page' => 1])) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="pagination-item disabled"><span class="pagination-link">…</span></li>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            <li class="pagination-item {{ $page === $paginator->currentPage() ? 'active' : '' }}">
                                @if($page === $paginator->currentPage())
                                    <span class="pagination-link">{{ $page }}</span>
                                @else
                                    <a class="pagination-link" href="{{ url()->current() . '?' . http_build_query(array_merge($qp, ['page' => $page])) }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endfor

                        @if($end < $paginator->lastPage())
                            @if($end < $paginator->lastPage() - 1)
                                <li class="pagination-item disabled"><span class="pagination-link">…</span></li>
                            @endif
                            <li class="pagination-item">
                                <a class="pagination-link" href="{{ url()->current() . '?' . http_build_query(array_merge($qp, ['page' => $paginator->lastPage()])) }}">{{ $paginator->lastPage() }}</a>
                            </li>
                        @endif

                        @php $next = min($paginator->lastPage(), $paginator->currentPage() + 1); @endphp
                        <li class="pagination-item {{ ! $paginator->hasMorePages() ? 'disabled' : '' }}">
                            @if(! $paginator->hasMorePages())
                                <span class="pagination-link">Next »</span>
                            @else
                                <a class="pagination-link" href="{{ url()->current() . '?' . http_build_query(array_merge($qp, ['page' => $next])) }}">Next »</a>
                            @endif
                        </li>
                    </ul>
                </nav>
            @endif
        </section>
    </div>
@endsection
