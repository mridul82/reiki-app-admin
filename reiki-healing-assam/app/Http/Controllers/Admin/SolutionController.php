<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RemedyType;
use App\Models\Solution;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolutionController extends Controller
{
    public function index()
    {
        $solutions = Solution::with('subcategory.category')->orderBy('subcategory_id')->orderBy('sort_order')->get();
        return view('admin.solutions.index', compact('solutions'));
    }

    public function create()
    {
        $subcategories = Subcategory::with('category')->where('is_active', true)->get();
        $remedyTypes   = RemedyType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->pluck('name');
        return view('admin.solutions.create', compact('subcategories', 'remedyTypes'));
    }

    public function store(Request $request)
    {
        $validTypes = RemedyType::where('is_active', true)->pluck('name')->toArray();
        $data = $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'remedy_type'    => 'required|in:' . implode(',', $validTypes),
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'image'          => 'nullable|image|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('solutions', 'uploads');
        }

        Solution::create([
            'subcategory_id' => $data['subcategory_id'],
            'remedy_type'    => $data['remedy_type'],
            'title'          => $data['title'],
            'content'        => $data['content'],
            'image_path'     => $imagePath,
            'sort_order'     => $data['sort_order'] ?? 0,
            'is_active'      => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.solutions.index')->with('success', 'Solution created.');
    }

    public function show(Solution $solution)
    {
        $solution->load('subcategory.category');
        return view('admin.solutions.show', compact('solution'));
    }

    public function edit(Solution $solution)
    {
        $subcategories = Subcategory::with('category')->where('is_active', true)->get();
        $remedyTypes   = RemedyType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->pluck('name');
        return view('admin.solutions.edit', compact('solution', 'subcategories', 'remedyTypes'));
    }

    public function update(Request $request, Solution $solution)
    {
        $validTypes = RemedyType::where('is_active', true)->pluck('name')->toArray();
        $data = $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'remedy_type'    => 'required|in:' . implode(',', $validTypes),
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'image'          => 'nullable|image|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable',
            'remove_image'   => 'nullable',
        ]);

        if ($request->boolean('remove_image') && $solution->image_path) {
            Storage::disk('uploads')->delete($solution->image_path);
            $solution->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($solution->image_path) {
                Storage::disk('uploads')->delete($solution->image_path);
            }
            $solution->image_path = $request->file('image')->store('solutions', 'uploads');
        }

        $solution->subcategory_id = $data['subcategory_id'];
        $solution->remedy_type    = $data['remedy_type'];
        $solution->title          = $data['title'];
        $solution->content        = $data['content'];
        $solution->sort_order     = $data['sort_order'] ?? 0;
        $solution->is_active      = $request->boolean('is_active');
        $solution->save();

        return redirect()->route('admin.solutions.index')->with('success', 'Solution updated.');
    }

    public function destroy(Solution $solution)
    {
        if ($solution->image_path) {
            Storage::disk('uploads')->delete($solution->image_path);
        }
        $solution->delete();
        return redirect()->route('admin.solutions.index')->with('success', 'Solution deleted.');
    }
}
