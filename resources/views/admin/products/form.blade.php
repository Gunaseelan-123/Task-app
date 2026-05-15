@extends('layouts.admin', ['title' => ($product->exists ? 'Edit Product' : 'Create Product').' | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Product editor</div><h1>{{ $product->exists ? 'Edit product' : 'Create product' }}</h1></div>
    </div>
    <section class="table-card">
        <form method="post" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="stack">
            @csrf
            @if($product->exists) @method('PUT') @endif
            <div class="field-row">
                <select class="field" name="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <input class="field" type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Product name">
            </div>
            <div class="field-row">
                <input class="field" type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="SKU">
                <input class="field" type="text" name="brand" value="{{ old('brand', $product->brand) }}" placeholder="Brand">
            </div>
            <input class="field" type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" placeholder="Short description">
            <textarea class="field" rows="5" name="description" placeholder="Description">{{ old('description', $product->description) }}</textarea>
            <div class="field-row">
                <input class="field" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" placeholder="Price">
                <input class="field" type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" placeholder="Compare price">
            </div>
            <div class="field-row">
                <input class="field" type="number" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="Stock">
                <input class="field" type="number" step="0.1" name="rating" value="{{ old('rating', $product->rating) }}" placeholder="Rating">
            </div>
            <div class="field-row">
                <input class="field" type="text" name="badge_text" value="{{ old('badge_text', $product->badge_text) }}" placeholder="Badge text">
                <input class="field" type="text" name="delivery_eta" value="{{ old('delivery_eta', $product->delivery_eta) }}" placeholder="Delivery ETA">
            </div>
            <textarea class="field" rows="3" name="search_keywords" placeholder="Search keywords">{{ old('search_keywords', $product->search_keywords) }}</textarea>
            <div class="field-row">
                <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
                <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
            </div>
            <button class="primary-btn" type="submit">Save product</button>
        </form>
    </section>
@endsection
