<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Sndpbag\AdminPanel\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,slug',
            'status' => 'required|in:Active,Inactive',
        ]);

        $data = $request->except('password', 'role');
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->assignRole($request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user->load('roles')
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user->load('roles', 'permissions')
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,slug',
            'status' => 'required|in:Active,Inactive',
        ]);

        $updateData = $request->except('password', 'role');
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->filled('role')) {
            $user->roles()->detach();
            $user->assignRole($request->role);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user->load('roles')
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User moved to trash successfully'
        ]);
    }
}
