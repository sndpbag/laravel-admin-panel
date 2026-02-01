<?php

namespace Sndpbag\AdminPanel\Traits;

use Sndpbag\AdminPanel\Models\Role;
use Sndpbag\AdminPanel\Models\Permission;

trait HasRoles
{
    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * The direct permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    /**
     * Check if user has a specific role (supports array or string).
     */
    public function hasRole($roles)
    {
        // Super Admin has all roles
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (is_string($roles)) {
            return $this->roles->contains('slug', $roles);
        }

        if (is_array($roles)) {
            return $this->roles->whereIn('slug', $roles)->isNotEmpty();
        }

        return false;
    }

    /**
     * Check if user has a specific permission.
     * This checks:
     * 1. Super Admin access
     * 2. Direct permissions
     * 3. Permissions via Roles (including recursive hierarchy)
     */
    public function hasPermission($permissionSlug)
    {
        // 1. Super Admin Bypass
        if ($this->isSuperAdmin()) {
            return true;
        }

        // 2. Check Direct Permissions
        if ($this->permissions->contains('slug', $permissionSlug)) {
            return true;
        }

        // 3. Check Permissions via Roles (Recursive)
        foreach ($this->roles as $role) {
            if ($this->roleHasPermission($role, $permissionSlug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($roleSlug)
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($roleSlug)
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Sync roles for the user (replace all existing roles).
     * Accepts a slug or an array of slugs.
     */
    public function syncRoles($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $roleIds = Role::whereIn('slug', $roles)->pluck('id')->toArray();
        $this->roles()->sync($roleIds);
    }

    /**
     * Give direct permission to user.
     */
    public function givePermission($permissionSlug)
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    /**
     * Recursive check for role permission inheritance.
     */
    protected function roleHasPermission($role, $permissionSlug)
    {
        // Check current role permissions
        if ($role->permissions->contains('slug', $permissionSlug)) {
            return true;
        }

        // Check parent role permissions (Recursion)
        if ($role->parent) {
            return $this->roleHasPermission($role->parent, $permissionSlug);
        }

        return false;
    }

    /**
     * Check if user is Super Admin.
     * Logic: Email check from config OR role check.
     */
    public function isSuperAdmin()
    {
        $superAdminEmail = config('admin-panel.super_admin_email');
        if ($superAdminEmail && $this->email === $superAdminEmail) {
            return true;
        }

        // Check for role directly to avoid infinite recursion with hasRole()
        return $this->roles->contains('slug', 'super-admin');
    }
}
