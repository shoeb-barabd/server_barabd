<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOnRequest;
use App\Models\AddOn;
use App\Models\Category;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    public function index(Request $request)
    {
        $q         = trim((string) $request->input('q',''));
        $unitType  = $request->input('unit_type');
        $status    = $request->boolean('only_active', false);
        $categoryId = $request->input('category_id');

        $addons = AddOn::when($q, function($qr) use ($q) {
                    $qr->where(function($w) use ($q){
                        $w->where('key','like',"%{$q}%")
                          ->orWhere('label','like',"%{$q}%")
                          ->orWhere('description','like',"%{$q}%");
                    });
                })
                ->when($unitType, fn($qr)=> $qr->where('unit_type',$unitType))
                ->when($status,   fn($qr)=> $qr->where('is_active',1))
                ->when($categoryId, fn($qr)=> $qr->where('category_id',$categoryId))
                ->orderBy('label')
                ->paginate(12)
                ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('back.add_ons.index', compact('addons','q','unitType','status','categoryId','categories'));
    }

    public function create()
    {
        $addon = new AddOn([
            'unit_type'    => 'recurring',
            'is_qty_based' => 0,
            'max_qty'      => 1,
            'is_active'    => 1,
        ]);
        $categories = Category::active()->orderBy('name')->get();
        return view('back.add_ons.create', compact('addon','categories'));
    }

    public function store(AddOnRequest $request)
    {
        AddOn::create($request->validated());
        return redirect()->route('admin.add-ons.index')->with('success','Add-on created.');
    }

    public function edit(AddOn $add_on)
    {
        $addon = $add_on;
        $categories = Category::active()->orderBy('name')->get();
        return view('back.add_ons.edit', compact('addon','categories'));
    }

    public function update(AddOnRequest $request, AddOn $add_on)
    {
        $add_on->update($request->validated());
        return redirect()->route('admin.add-ons.index')->with('success','Add-on updated.');
    }

    public function destroy(AddOn $add_on)
    {
        $add_on->delete();
        return back()->with('success','Add-on deleted.');
    }
}
