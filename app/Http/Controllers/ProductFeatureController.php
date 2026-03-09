<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFeatureRequest;
use App\Models\ProductFeature;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductFeatureController extends Controller
{
    public function index(Request $request)
    {
        $q          = trim((string) $request->input('q',''));
        $productId  = $request->input('product_id');
        $inputType  = $request->input('input_type');

        $features = ProductFeature::with('product:id,name')
            ->when($q, function($qr) use ($q) {
                $qr->where(function($w) use ($q) {
                    $w->where('key','like',"%{$q}%")
                      ->orWhere('label','like',"%{$q}%");
                });
            })
            ->when($productId, fn($qr)=> $qr->where('product_id', (int) $productId))
            ->when($inputType, fn($qr)=> $qr->where('input_type', $inputType))
            ->orderBy('product_id')->orderBy('key')
            ->paginate(12)->withQueryString();

        $products = Product::orderBy('name')->get(['id','name']);

        return view('back.product_feature.index', compact('features','products','q','productId','inputType'));
    }

    public function create()
    {
        $feature  = new ProductFeature([
            'input_type'  => 'number',
            'is_required' => 1,
        ]);
        $products = Product::orderBy('name')->get(['id','name']);

        return view('back.product_feature.create', compact('feature','products'));
    }

    public function store(ProductFeatureRequest $request)
    {
        ProductFeature::create($request->validated());
        return redirect()->route('admin.product-features.index')->with('success','Feature created.');
    }

    public function edit(ProductFeature $product_feature)
    {
        $feature  = $product_feature;
        $products = Product::orderBy('name')->get(['id','name']);

        return view('back.product_feature.edit', compact('feature','products'));
    }

    public function update(ProductFeatureRequest $request, ProductFeature $product_feature)
    {
        $product_feature->update($request->validated());
        return redirect()->route('admin.product-features.index')->with('success','Feature updated.');
    }

    public function destroy(ProductFeature $product_feature)
    {
        $product_feature->delete();
        return back()->with('success','Feature deleted.');
    }
}
