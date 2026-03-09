<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::orderByDesc('created_at')->paginate(10);
        $current = $offers->firstWhere('is_active', true);

        return view('back.offers.index', compact('offers', 'current'));
    }

    public function store(OfferRequest $request)
    {
        // If this new offer is active, deactivate others
        if ($request->boolean('is_active')) {
            Offer::where('is_active', true)->update(['is_active' => false]);
        }

        Offer::create($request->validated());

        return redirect()->route('admin.offers.index')->with('success', 'Offer saved successfully.');
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        if ($request->boolean('is_active')) {
            Offer::where('is_active', true)->where('id', '!=', $offer->id)->update(['is_active' => false]);
        }

        $offer->update($request->validated());

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function edit(Offer $offer)
    {
        return view('back.offers.edit', compact('offer'));
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }
}
