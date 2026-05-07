<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use App\Models\Subcategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = $request->user()
            ->reports()
            ->latest()
            ->get(['id', 'module', 'customer_first_name', 'customer_last_name', 'customer_dob', 'customer_contact', 'subcategory_ids', 'created_at']);

        return response()->json($reports);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'subcategory_ids'     => 'required|array|min:1',
            'subcategory_ids.*'   => 'integer|exists:subcategories,id',
            'customer.first_name' => 'required|string|max:100',
            'customer.last_name'  => 'required|string|max:100',
            'customer.dob'        => 'required|date',
            'customer.contact'    => 'required|string|max:20',
        ]);

        $customer = $validated['customer'];
        $category = Category::find($validated['category_id']);

        $subcategories = Subcategory::whereIn('id', $validated['subcategory_ids'])
            ->where('is_active', true)
            ->with(['solutions' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->get();

        $report = Report::create([
            'user_id'             => $request->user()->id,
            'module'              => $category->name,
            'customer_first_name' => $customer['first_name'],
            'customer_last_name'  => $customer['last_name'],
            'customer_dob'        => $customer['dob'],
            'customer_contact'    => $customer['contact'],
            'subcategory_ids'     => $validated['subcategory_ids'],
        ]);

        $pdf = Pdf::loadView('reports.pdf', [
            'report'        => $report,
            'customer'      => $customer,
            'module'        => $category->name,
            'subcategories' => $subcategories,
            'client'        => $request->user(),
        ]);

        return $pdf->download("reiki-report-{$customer['first_name']}-{$customer['last_name']}.pdf");
    }

    public function download(Request $request, Report $report)
    {
        // Ensure the report belongs to the authenticated client
        if ($report->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $subcategories = Subcategory::whereIn('id', $report->subcategory_ids)
            ->where('is_active', true)
            ->with(['solutions' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->get();

        $pdf = Pdf::loadView('reports.pdf', [
            'report'        => $report,
            'customer'      => [
                'first_name' => $report->customer_first_name,
                'last_name'  => $report->customer_last_name,
                'dob'        => $report->customer_dob->format('Y-m-d'),
                'contact'    => $report->customer_contact,
            ],
            'module'        => $report->module,
            'subcategories' => $subcategories,
            'client'        => $request->user(),
        ]);

        return $pdf->download("reiki-report-{$report->customer_first_name}-{$report->customer_last_name}.pdf");
    }
}
