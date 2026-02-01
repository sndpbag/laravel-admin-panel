<?php

namespace Sndpbag\AdminPanel\Database\Seeders;

use Illuminate\Database\Seeder;
use Sndpbag\AdminPanel\Models\Role;
use Sndpbag\AdminPanel\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Sync Routes to create permissions
        $this->command->info('Syncing routes to permissions...');
        Artisan::call('dynamic-roles:sync-routes');

        // 2. Create Roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Super Administrator with full access']
        );

        $editorRole = Role::firstOrCreate(
            ['slug' => 'editor'],
            ['name' => 'Editor', 'description' => 'Can manage content but cannot manage users/settings']
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            ['name' => 'User', 'description' => 'Standard user with limited access']
        );

        // 3. Assign Permissions to Roles

        // Admin gets ALL permissions
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions);

        // Editor gets specific permissions (Example logic: exclude user/role/settings management)
        // We filter permissions that DON'T start with 'users.', 'roles.', 'settings.'
        $editorPermissions = $allPermissions->filter(function ($permission) {
            return !str_starts_with($permission->slug, 'users.') &&
                !str_starts_with($permission->slug, 'roles.') &&
                !str_starts_with($permission->slug, 'settings.');
        });
        $editorRole->permissions()->sync($editorPermissions);

        // User gets basic permissions (e.g., dashboard, profile, etc.)
        // This is flexible, for now let's give them dashboard access
        $userPermissions = $allPermissions->filter(function ($permission) {
            return str_starts_with($permission->slug, 'dashboard') ||
                str_starts_with($permission->slug, 'profile');
        });
        $userRole->permissions()->sync($userPermissions);

        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
