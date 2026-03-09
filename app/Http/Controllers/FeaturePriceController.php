<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeaturePriceRequest;
use App\Models\CustomizeFeaturePrice;
use App\Models\CustomizeFeature;
use App\Models\Location;
use App\Models\BillingCycle;
use Illuminate\Http\Request;

class FeaturePriceController extends Controller
{
    public function index(Request $request)
    {
        $featureId = $request->input('customize_feature_id');
        $locationId= $request->input('location_id');
        $cycleId   = $request->input('billing_cycle_id');

        $rows = CustomizeFeaturePrice::with(['feature:id,label,key,category_id','feature.category:id,name','location:id,name','billingCycle:id,name'])
            ->when($featureId, fn($q)=>$q->where('customize_feature_id',$featureId))
            ->when($locationId,fn($q)=>$q->where('location_id',$locationId))
            ->when($cycleId,  fn($q)=>$q->where('billing_cycle_id',$cycleId))
            ->orderByDesc('id')->paginate(50)->withQueryString();

        $features  = CustomizeFeature::with('category:id,name')->orderBy('category_id')->orderBy('label')->get();
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);

        return view('back.feature_prices.index', compact('rows','features','locations','cycles','featureId','locationId','cycleId'));
    }

    public function create()
    {
        $row = new CustomizeFeaturePrice(['step'=>1]);
        $features  = CustomizeFeature::with('category:id,name')->orderBy('category_id')->orderBy('label')->get();
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.feature_prices.create', compact('row','features','locations','cycles'));
    }

    public function store(FeaturePriceRequest $request)
    {
        CustomizeFeaturePrice::create($request->validated());
        return redirect()->route('admin.feature-prices.index')->with('success','Feature price created.');
    }

    public function edit(CustomizeFeaturePrice $feature_price)
    {
        $row = $feature_price;
        $features  = CustomizeFeature::with('category:id,name')->orderBy('category_id')->orderBy('label')->get();
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.feature_prices.edit', compact('row','features','locations','cycles'));
    }

    public function update(FeaturePriceRequest $request, CustomizeFeaturePrice $feature_price)
    {
        $feature_price->update($request->validated());
        return redirect()->route('admin.feature-prices.index')->with('success','Feature price updated.');
    }

    public function destroy(CustomizeFeaturePrice $feature_price)
    {
        $feature_price->delete();
        return back()->with('success','Feature price deleted.');
    }
}
