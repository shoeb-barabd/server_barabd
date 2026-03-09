<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOnPriceRequest;
use App\Models\AddOnPrice;
use App\Models\AddOn;
use App\Models\Location;
use App\Models\BillingCycle;
use Illuminate\Http\Request;

class AddOnPriceController extends Controller
{
    public function index(Request $request)
    {
        $addonId   = $request->input('add_on_id');
        $locationId= $request->input('location_id');
        $cycleId   = $request->input('billing_cycle_id');

        $rows = AddOnPrice::with(['addOn:id,label,key','location:id,name','billingCycle:id,name'])
            ->when($addonId,   fn($q)=>$q->where('add_on_id',$addonId))
            ->when($locationId,fn($q)=>$q->where('location_id',$locationId))
            ->when($cycleId,   fn($q)=>$q->where('billing_cycle_id',$cycleId))
            ->orderByDesc('id')->paginate(50)->withQueryString();

        $addons    = AddOn::orderBy('label')->get(['id','label','key']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);

        return view('back.add_on_prices.index', compact('rows','addons','locations','cycles','addonId','locationId','cycleId'));
    }

    public function create()
    {
        $row = new AddOnPrice();
        $addons    = AddOn::orderBy('label')->get(['id','label','key']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.add_on_prices.create', compact('row','addons','locations','cycles'));
    }

    public function store(AddOnPriceRequest $request)
    {
        AddOnPrice::create($request->validated());
        return redirect()->route('admin.add-on-prices.index')->with('success','Add-on price created.');
    }

    public function edit(AddOnPrice $add_on_price)
    {
        $row = $add_on_price;
        $addons    = AddOn::orderBy('label')->get(['id','label','key']);
        $locations = Location::orderBy('name')->get(['id','name']);
        $cycles    = BillingCycle::orderBy('months')->get(['id','name']);
        return view('back.add_on_prices.edit', compact('row','addons','locations','cycles'));
    }

    public function update(AddOnPriceRequest $request, AddOnPrice $add_on_price)
    {
        $add_on_price->update($request->validated());
        return redirect()->route('admin.add-on-prices.index')->with('success','Add-on price updated.');
    }

    public function destroy(AddOnPrice $add_on_price)
    {
        $add_on_price->delete();
        return back()->with('success','Add-on price deleted.');
    }
}
