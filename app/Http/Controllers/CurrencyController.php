<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q',''));
        $rows = Currency::query()
            ->when($q !== '', fn($qq) => $qq->where('code','like',"%$q%")
                ->orWhere('name','like',"%$q%"))
            ->orderBy('code')
            ->paginate(15)->withQueryString();

        return view('back.currencies.index', ['currencies'=>$rows,'q'=>$q]);
    }

    public function create()
    {
        return view('back.currencies.create', ['currency'=>new Currency]);
    }

    public function store(CurrencyRequest $req)
    {
        Currency::create($req->validated());
        return redirect()->route('admin.currencies.index')->with('success','Currency created.');
    }

    public function edit(Currency $currency)
    {
        return view('back.currencies.edit', compact('currency'));
    }

    public function update(CurrencyRequest $req, Currency $currency)
    {
        $currency->update($req->validated());
        return redirect()->route('admin.currencies.index')->with('success','Currency updated.');
    }

    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
            return back()->with('success','Currency deleted.');
        } catch (\Throwable $e) {
            return back()->with('error','Unable to delete currency (in use).');
        }
    }
}
