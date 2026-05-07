<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Subcategory;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('client')
            ->latest()
            ->paginate(25);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('client');

        $subcategories = Subcategory::whereIn('id', $report->subcategory_ids)
            ->with(['solutions' => fn($q) => $q->where('is_active', true)->orderBy('remedy_type')->orderBy('sort_order')])
            ->get();

        return view('admin.reports.show', compact('report', 'subcategories'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('admin.reports.index')->with('success', 'Report deleted.');
    }
}
