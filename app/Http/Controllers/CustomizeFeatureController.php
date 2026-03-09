<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomizeFeatureRequest;
use App\Models\CustomizeFeature;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomizeFeatureController extends Controller
{
    public function index(Request $request)
    {
        $q          = trim((string) $request->input('q', ''));
        $categoryId = $request->input('category_id');
        $inputType  = $request->input('input_type');

        $features = CustomizeFeature::with('category:id,name')
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('key', 'like', "%{$q}%")
                        ->orWhere('label', 'like', "%{$q}%");
                });
            })
            ->when($categoryId, fn($qr) => $qr->where('category_id', (int) $categoryId))
            ->when($inputType, fn($qr) => $qr->where('input_type', $inputType))
            ->orderBy('category_id')
            ->orderBy('key')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('back.customize_features.index', compact('features', 'categories', 'q', 'categoryId', 'inputType'));
    }

    public function create()
    {
        $feature    = new CustomizeFeature(['input_type' => 'number', 'is_required' => 1]);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('back.customize_features.create', compact('feature', 'categories'));
    }

    public function store(CustomizeFeatureRequest $request)
    {
        $data = $this->normalizeOptions($request->validated());
        CustomizeFeature::create($data);
        return redirect()->route('admin.customize-features.index')->with('success', 'Customize feature created.');
    }

    public function edit(CustomizeFeature $customize_feature)
    {
        $feature    = $customize_feature;
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('back.customize_features.edit', compact('feature', 'categories'));
    }

    public function update(CustomizeFeatureRequest $request, CustomizeFeature $customize_feature)
    {
        $data = $this->normalizeOptions($request->validated());
        $customize_feature->update($data);
        return redirect()->route('admin.customize-features.index')->with('success', 'Customize feature updated.');
    }

    public function destroy(CustomizeFeature $customize_feature)
    {
        $customize_feature->delete();
        return back()->with('success', 'Customize feature deleted.');
    }

    protected function normalizeOptions(array $data): array
    {
        if (isset($data['options_json'])) {
            $decoded = json_decode($data['options_json'], true);
            $data['options_json'] = is_array($decoded) ? $decoded : null;
        }
        return $data;
    }
}
