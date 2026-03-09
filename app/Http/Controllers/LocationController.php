<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q', ''));
        $status = $request->has('status') ? (string) $request->input('status') : null; // '1' or '0' or null

        $locations = Location::when($q, function($qr) use($q) {
                $qr->where('name', 'like', "%{$q}%")
                   ->orWhere('currency_code', 'like', "%{$q}%");
            })
            ->when($status !== null && $status !== '', function($qr) use ($status) {
                $qr->where('is_active', (bool) (int) $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('back.locations.index', compact('locations','q','status'));
    }

    public function create()
    {
        $location = new Location([
            'is_active'        => true,
            'currency_code'    => 'BDT',
            'tax_rate_percent' => 0,
        ]);
        return view('back.locations.create', compact('location'));
    }

    public function store(LocationRequest $request)
    {
        Location::create($request->validated());
        return redirect()->route('admin.locations.index')->with('success', 'Location created.');
    }

    public function edit(Location $location)
    {
        return view('back.locations.edit', compact('location'));
    }

    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->validated());
        return redirect()->route('admin.locations.index')->with('success', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $location->delete(); // SoftDeletes supported in your model
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted.');
    }
}
