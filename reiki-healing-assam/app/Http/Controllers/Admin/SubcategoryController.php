<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->withCount('solutions')->orderBy('category_id')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable',
        ]);

        Subcategory::create([
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created.');
    }

    public function show(Subcategory $subcategory) {}

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable',
        ]);

        $subcategory->update([
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted.');
    }
}
