<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainTld;
use Illuminate\Http\Request;

class DomainTldController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $tlds = DomainTld::query()
            ->when($q, fn($query) => $query->where('tld', 'like', "%{$q}%"))
            ->orderBy('sort_order')
            ->orderBy('tld')
            ->paginate(20)
            ->withQueryString();

        return view('back.domain_tlds.index', compact('tlds', 'q'));
    }

    public function create()
    {
        return view('back.domain_tlds.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tld'            => 'required|string|max:20|unique:domain_tlds,tld',
            'register_price' => 'required|numeric|min:0',
            'renew_price'    => 'required|numeric|min:0',
            'transfer_price' => 'nullable|numeric|min:0',
            'currency'       => 'nullable|string|max:5',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        // Ensure TLD starts with a dot
        if (!str_starts_with($data['tld'], '.')) {
            $data['tld'] = '.' . $data['tld'];
        }
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['transfer_price'] = $data['transfer_price'] ?? 0;
        $data['currency']   = $data['currency'] ?? 'BDT';

        DomainTld::create($data);

        return redirect()->route('admin.domain-tlds.index')
            ->with('success', 'TLD added successfully.');
    }

    public function edit(DomainTld $domain_tld)
    {
        return view('back.domain_tlds.edit', ['tld' => $domain_tld]);
    }

    public function update(Request $request, DomainTld $domain_tld)
    {
        $data = $request->validate([
            'tld'            => 'required|string|max:20|unique:domain_tlds,tld,' . $domain_tld->id,
            'register_price' => 'required|numeric|min:0',
            'renew_price'    => 'required|numeric|min:0',
            'transfer_price' => 'nullable|numeric|min:0',
            'currency'       => 'nullable|string|max:5',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        if (!str_starts_with($data['tld'], '.')) {
            $data['tld'] = '.' . $data['tld'];
        }
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['transfer_price'] = $data['transfer_price'] ?? 0;
        $data['currency']   = $data['currency'] ?? 'BDT';

        $domain_tld->update($data);

        return redirect()->route('admin.domain-tlds.index')
            ->with('success', 'TLD updated successfully.');
    }

    public function destroy(DomainTld $domain_tld)
    {
        $domain_tld->delete();

        return redirect()->route('admin.domain-tlds.index')
            ->with('success', 'TLD deleted.');
    }
}
