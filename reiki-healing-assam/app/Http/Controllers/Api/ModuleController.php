<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Solution;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    // GET /api/categories — list all active categories for the SPA dashboard
    public function categories()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name', 'description']);
        return response()->json($categories);
    }

    // GET /api/categories/{category}/subcategories — list subcategories for a category
    public function subcategories(Category $category)
    {
        if (!$category->is_active) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $subcategories = Subcategory::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category:id,name')
            ->get(['id', 'category_id', 'name', 'description']);

        return response()->json($subcategories);
    }

    // GET /api/solutions/search?q=keyword — search solutions by title or content
    public function searchSolutions(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $solutions = Solution::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
            })
            ->with('subcategory:id,category_id,name')
            ->orderBy('title')
            ->limit(30)
            ->get(['id', 'subcategory_id', 'remedy_type', 'title', 'content', 'image_path']);

        return response()->json($solutions);
    }

    // POST /api/subcategories/solutions — preview solutions for selected subcategory IDs
    public function solutionsPreview(Request $request)
    {
        $validated = $request->validate([
            'subcategory_ids'   => 'required|array|min:1',
            'subcategory_ids.*' => 'integer|exists:subcategories,id',
        ]);

        $subcategories = Subcategory::whereIn('id', $validated['subcategory_ids'])
            ->where('is_active', true)
            ->with(['solutions' => fn($q) => $q->where('is_active', true)->orderBy('remedy_type')->orderBy('sort_order')])
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }
}
