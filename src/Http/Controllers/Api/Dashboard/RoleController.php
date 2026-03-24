<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\Role;
use Sndpbag\AdminPanel\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::query()->withCount('users');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        }

        $roles = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'role' => $role->load('permissions')
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => 'success',
            'role' => $role->load('permissions')
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete role because it is assigned to users.'
            ], 422);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }
}
