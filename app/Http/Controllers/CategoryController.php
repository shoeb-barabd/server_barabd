<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $categories = Category::when($q, function ($query) use ($q) {
                return $query->where('name', 'like', "%{$q}%")
                             ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('back.categories.index', compact('categories', 'q'));
    }

    public function create()
    {
        $category = new Category(['is_active' => 1]);
        return view('back.categories.create', compact('category'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('back.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
