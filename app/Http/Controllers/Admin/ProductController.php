<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->with('category')->latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::query()->create($this->validatedData($request));

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedData($request, $product->id));

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function validatedData(Request $request, ?int $productId = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,'.($productId ?? 'NULL').',id'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku,'.($productId ?? 'NULL').',id'],
            'brand' => ['nullable', 'string', 'max:120'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'badge_text' => ['nullable', 'string', 'max:80'],
            'delivery_eta' => ['nullable', 'string', 'max:80'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'search_keywords' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['rating'] = $data['rating'] ?? 0;

        return $data;
    }
}
