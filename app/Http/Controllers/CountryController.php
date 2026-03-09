<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\CountryRequest;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $countries = Country::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('iso2', 'like', "%{$q}%")
                        ->orWhere('iso3', 'like', "%{$q}%")
                        ->orWhere('phone_code', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('back.countries.index', compact('countries','q'));
    }

    public function create()
    {
        $country = new Country();
        return view('back.countries.create', compact('country'));
    }

    public function store(CountryRequest $request)
    {
        Country::create($request->validated());
        return redirect()->route('admin.countries.index')->with('success', 'Country created.');
    }

    public function edit(Country $country)
    {
        return view('back.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->update($request->validated());
        return redirect()->route('admin.countries.index')->with('success', 'Country updated.');
    }

    public function destroy(Country $country)
    {
        try {
            DB::transaction(function () use ($country) {
                $country->delete(); // soft delete
            });
            return back()->with('success', 'Country deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete this country (in use).');
        }
    }
}
