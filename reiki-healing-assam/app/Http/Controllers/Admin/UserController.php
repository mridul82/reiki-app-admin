<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'client')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:255',
            'email'                   => 'required|email|unique:users,email',
            'password'                => 'required|string|min:8',
            'phone'                   => 'nullable|string|max:20',
            'subscription_expires_at' => 'nullable|date',
            'is_active'               => 'nullable',
        ]);

        User::create([
            'name'                    => $data['name'],
            'email'                   => $data['email'],
            'password'                => Hash::make($data['password']),
            'phone'                   => $data['phone'] ?? null,
            'role'                    => 'client',
            'is_active'               => $request->boolean('is_active'),
            'subscription_expires_at' => $data['subscription_expires_at'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Client created successfully.');
    }

    public function show(User $user) {}

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:255',
            'email'                   => 'required|email|unique:users,email,' . $user->id,
            'password'                => 'nullable|string|min:8',
            'phone'                   => 'nullable|string|max:20',
            'subscription_expires_at' => 'nullable|date',
            'is_active'               => 'nullable',
        ]);

        $user->name                    = $data['name'];
        $user->email                   = $data['email'];
        $user->phone                   = $data['phone'] ?? null;
        $user->is_active               = $request->boolean('is_active');
        $user->subscription_expires_at = $data['subscription_expires_at'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Client deleted.');
    }
}
