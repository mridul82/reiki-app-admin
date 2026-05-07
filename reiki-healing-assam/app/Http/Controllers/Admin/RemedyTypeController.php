<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RemedyType;
use Illuminate\Http\Request;

class RemedyTypeController extends Controller
{
    public function index()
    {
        $remedyTypes = RemedyType::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.remedy_types.index', compact('remedyTypes'));
    }

    public function create()
    {
        return view('admin.remedy_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100|unique:remedy_types,name',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        RemedyType::create([
            'name'       => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.remedy-types.index')->with('success', 'Remedy type created.');
    }

    public function edit(RemedyType $remedyType)
    {
        return view('admin.remedy_types.edit', compact('remedyType'));
    }

    public function update(Request $request, RemedyType $remedyType)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100|unique:remedy_types,name,' . $remedyType->id,
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        $remedyType->update([
            'name'       => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.remedy-types.index')->with('success', 'Remedy type updated.');
    }

    public function destroy(RemedyType $remedyType)
    {
        $remedyType->delete();
        return redirect()->route('admin.remedy-types.index')->with('success', 'Remedy type deleted.');
    }
}
