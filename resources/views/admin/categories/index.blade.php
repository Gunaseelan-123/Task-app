@extends('layouts.admin', ['title' => 'Categories | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Catalog taxonomy</div><h1>Categories</h1></div>
    </div>
    <div class="admin-grid">
        <section class="table-card">
            <h3>Add category</h3>
            <form method="post" action="{{ route('admin.categories.store') }}" class="stack">
                @csrf
                <input class="field" type="text" name="name" placeholder="Category name">
                <input class="field" type="text" name="description" placeholder="Description">
                <input class="field" type="number" name="sort_order" placeholder="Sort order">
                <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
                <button class="primary-btn" type="submit">Save</button>
            </form>
        </section>
        <section class="table-card" style="grid-column: span 2;">
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Name</th><th>Products</th><th>Sort</th><th>Actions</th></tr></thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <form class="inline-form" method="post" action="{{ route('admin.categories.destroy', $category) }}">
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
        </section>
    </div>
@endsection
