<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingCycleRequest;
use App\Models\BillingCycle;
use Illuminate\Http\Request;

class BillingCycleController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $billingCycles = BillingCycle::when($q, function ($query) use ($q) {
                return $query->where('key', 'like', "%{$q}%")
                             ->orWhere('name', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('back.billing_cycles.index', compact('billingCycles', 'q'));
    }

    public function create()
    {
        $billingCycle = new BillingCycle(['is_active' => 1]);
        return view('back.billing_cycles.create', compact('billingCycle'));
    }

    public function store(BillingCycleRequest $request)
    {
        BillingCycle::create($request->validated());
        return redirect()->route('admin.billing-cycles.index')->with('success', 'Billing cycle created successfully!');
    }

    public function edit(BillingCycle $billingCycle)
    {
        return view('back.billing_cycles.edit', compact('billingCycle'));
    }

    public function update(BillingCycleRequest $request, BillingCycle $billingCycle)
    {
        $billingCycle->update($request->validated());
        return redirect()->route('admin.billing-cycles.index')->with('success', 'Billing cycle updated successfully!');
    }

    public function destroy(BillingCycle $billingCycle)
    {
        $billingCycle->delete();
        return back()->with('success', 'Billing cycle deleted successfully!');
    }
}
