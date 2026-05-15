@extends('layouts.admin', ['title' => 'Products | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Catalog</div><h1>Products</h1></div>
        <a href="{{ route('admin.products.create') }}" class="primary-btn">Add product</a>
    </div>
    <section class="table-card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td>Rs. {{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a class="ghost-btn" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form class="inline-form" action="{{ route('admin.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="ghost-btn" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $products->links() }}</div>
    </section>
@endsection
