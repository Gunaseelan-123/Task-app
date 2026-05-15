@extends('layouts.app', ['title' => 'Wishlist | Northstar'])

@section('content')
    <section class="section">
        <div class="section-head">
            <div>
                <div class="eyebrow">Wishlist</div>
                <h2>Saved for later</h2>
            </div>
        </div>
        <div class="card-grid">
            @forelse($items as $item)
                <article class="product-card">
                    <div class="product-media">
                        <img class="product-image" src="{{ $item->product?->primary_image }}" alt="{{ $item->product?->name }}">
                    </div>
                    <div class="product-body">
                        <h3 class="product-title">{{ $item->product?->name }}</h3>
                        <div class="price"><span>Rs. {{ number_format($item->product?->price ?? 0, 2) }}</span></div>
                        <div class="product-card__cta">
                            <a href="{{ route('products.show', $item->product) }}" class="primary-btn">View</a>
                            <form action="{{ route('wishlist.destroy', $item) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="ghost-btn" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel empty-state">Your wishlist is empty.</div>
            @endforelse
        </div>
    </section>
@endsection
