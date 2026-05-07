<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RemedyTypeController;
use App\Http\Controllers\Admin\SolutionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin auth routes
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');

Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials) && Auth::user()->isAdmin()) {
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    Auth::logout();
    return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
})->name('admin.login.post');

Route::post('/admin/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

// Admin panel (protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('solutions', SolutionController::class);
    Route::resource('remedy-types', RemedyTypeController::class)->except(['show']);
    Route::resource('reports', ReportController::class)->only(['index', 'show', 'destroy']);
});

// Catch-all: serve React SPA for client routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!admin).*$')->name('spa');
