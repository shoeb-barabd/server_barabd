<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresetRequest;
use App\Models\Preset;
use App\Models\Product;
use App\Models\AddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PresetController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->input('product_id');

        $rows = Preset::with('product:id,name')
            ->when($productId, fn($q)=>$q->where('product_id',$productId))
            ->orderBy('product_id')->orderBy('sort_order')
            ->paginate(12)->withQueryString();

        $products = Product::orderBy('name')->get(['id','name']);

        return view('back.presets.index', compact('rows','products','productId'));
    }

    public function create()
    {
        $row = new Preset(['is_featured'=>0,'sort_order'=>0]);
        $products = Product::orderBy('name')->get(['id','name']);
        $addons   = AddOn::where('is_active',1)->orderBy('label')->get(['id','label','key']);
        return view('back.presets.create', compact('row','products','addons'));
    }

    public function store(PresetRequest $request)
    {
        $data = $request->validated();
        // auto slug if blank
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        Preset::create($data);
        return redirect()->route('admin.presets.index')->with('success','Preset created.');
    }

    public function edit(Preset $preset)
    {
        $row = $preset;
        $products = Product::orderBy('name')->get(['id','name']);
        $addons   = AddOn::where('is_active',1)->orderBy('label')->get(['id','label','key']);
        return view('back.presets.edit', compact('row','products','addons'));
    }

    public function update(PresetRequest $request, Preset $preset)
    {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $preset->update($data);
        return redirect()->route('admin.presets.index')->with('success','Preset updated.');
    }

    public function destroy(Preset $preset)
    {
        $preset->delete();
        return back()->with('success','Preset deleted.');
    }
}
