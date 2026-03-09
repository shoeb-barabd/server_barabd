<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePriceRequest;
use App\Models\BasePrice;
use App\Models\Product;
use App\Models\Location;
use App\Models\BillingCycle;
use Illuminate\Http\Request;

class BasePriceController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->input('product_id');
        $locationId= $request->input('location_id');
        $cycleId   = $request->input('billing_cycle_id');

        $rows = BasePrice::with(['product:id,name','location:id,name','billingCycle:id,name'])
            ->when($productId, fn($q)=>$q->where('product_id',$productId))
            ->when($locationId,fn($q)=>$q->where('location_id',$locationId))
            ->when($cycleId,  fn($q)=>$q->where('billing_cycle_id',$cycleId))
            ->orderByDesc('id')
            ->paginate(12)->withQueryString();

        $products  = Product::orderBy('name')->get(['id','name']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);

        return view('back.base_prices.index', compact('rows','products','locations','cycles','productId','locationId','cycleId'));
    }

    public function create()
    {
        $row = new BasePrice();
        $products  = Product::orderBy('name')->get(['id','name']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.base_prices.create', compact('row','products','locations','cycles'));
    }

    public function store(BasePriceRequest $request)
    {
        BasePrice::create($request->validated());
        return redirect()->route('admin.base-prices.index')->with('success','Base price created.');
    }

    public function edit(BasePrice $base_price)
    {
        $row = $base_price;
        $products  = Product::orderBy('name')->get(['id','name']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.base_prices.edit', compact('row','products','locations','cycles'));
    }

    public function update(BasePriceRequest $request, BasePrice $base_price)
    {
        $base_price->update($request->validated());
        return redirect()->route('admin.base-prices.index')->with('success','Base price updated.');
    }

    public function destroy(BasePrice $base_price)
    {
        $base_price->delete();
        return back()->with('success','Base price deleted.');
    }
}
