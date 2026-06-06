@extends('layouts.app', ['title' => 'Shop | Northstar'])

@section('content')
    <div class="shop-layout">
        <aside class="filters">
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
                <nav class="pagination-container" aria-label="Product pagination">
                    <ul class="pagination-list">
                        <li class="pagination-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            @if($products->onFirstPage())
                                <span class="pagination-link">« Previous</span>
                            @else
                                <a class="pagination-link" href="{{ $products->previousPageUrl() }}">« Previous</a>
                            @endif
                        </li>

                        @php
                            $start = max(1, $products->currentPage() - 2);
                            $end = min($products->lastPage(), $products->currentPage() + 2);
                        @endphp

                        @if($start > 1)
                            <li class="pagination-item">
                                <a class="pagination-link" href="{{ $products->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="pagination-item disabled"><span class="pagination-link">…</span></li>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            <li class="pagination-item {{ $page === $products->currentPage() ? 'active' : '' }}">
                                @if($page === $products->currentPage())
                                    <span class="pagination-link">{{ $page }}</span>
                                @else
                                    <a class="pagination-link" href="{{ $products->url($page) }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endfor

                        @if($end < $products->lastPage())
                            @if($end < $products->lastPage() - 1)
                                <li class="pagination-item disabled"><span class="pagination-link">…</span></li>
                            @endif
                            <li class="pagination-item">
                                <a class="pagination-link" href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
                            </li>
                        @endif

                        <li class="pagination-item {{ ! $products->hasMorePages() ? 'disabled' : '' }}">
                            @if(! $products->hasMorePages())
                                <span class="pagination-link">Next »</span>
                            @else
                                <a class="pagination-link" href="{{ $products->nextPageUrl() }}">Next »</a>
                            @endif
                        </li>
                    </ul>
                </nav>
            @endif
        </section>
    </div>
@endsection
