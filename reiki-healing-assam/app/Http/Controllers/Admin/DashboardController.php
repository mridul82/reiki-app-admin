<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use App\Models\Solution;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clients'        => User::where('role', 'client')->count(),
            'active_clients' => User::where('role', 'client')->where('is_active', true)
                                    ->where('subscription_expires_at', '>', now())->count(),
            'categories'     => Category::count(),
            'solutions'      => Solution::count(),
            'reports'        => Report::count(),
        ];

        $categories_overview = Category::withCount('subcategories')->orderBy('name')->get();

        $recent_clients = User::where('role', 'client')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'categories_overview', 'recent_clients'));
    }
}
