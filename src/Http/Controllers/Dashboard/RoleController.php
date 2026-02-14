<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\Role;
use Sndpbag\AdminPanel\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Show security password check page
     */
    public function showSecurityCheck()
    {
        return view('admin-panel::dashboard.roles.security-check');
    }

    /**
     * Verify security password from .env
     */
    public function verifySecurityPassword(Request $request)
    {
        $request->validate([
            'security_password' => 'required|string',
        ]);

        $envPassword = env('ROLES_SECURITY_PASSWORD');

        if ($request->security_password === $envPassword) {
            session(['roles_security_verified' => true]);

            // Redirect to intended URL or roles index
            $intendedUrl = session('intended_url', route('roles.index'));
            session()->forget('intended_url');

            return redirect($intendedUrl)->with('success', 'Access granted!');
        }

        return back()->withErrors([
            'security_password' => 'Invalid security key. Please try again.'
        ])->withInput();
    }

    /**
     * Clear security verification session
     */
    public function clearSecuritySession()
    {
        session()->forget('roles_security_verified');
        return redirect()->route('dashboard')->with('success', 'Security session cleared.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query()->withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        }

        $roles = $query->paginate(10);

        return view('admin-panel::dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Group permissions by group_name for the UI
        $permissionGroups = Permission::all()->groupBy('group_name');
        return view('admin-panel::dashboard.roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissionGroups = Permission::all()->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin-panel::dashboard.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        } else {
            $role->permissions()->detach(); // If no permissions sent, remove all
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role because it is assigned to users.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
