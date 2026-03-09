<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $products = Product::with('category')
            ->when($q, function ($query) use ($q) {
                return $query->where('name', 'like', "%{$q}%")
                             ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('back.products.index', compact('products', 'q'));
    }

    public function create()
    {
        $categories = Category::all();
        $product = new Product(['is_active' => 1]);
        return view('back.products.create', compact('product', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('back.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }
}
