<?php

namespace Sndpbag\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Sndpbag\AdminPanel\Models\User;
use Sndpbag\AdminPanel\Models\Role;
use Sndpbag\AdminPanel\Models\Permission;

class AssignRoleOrPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-panel:assign-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a Role or direct Permission to a specific user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('User Access Management Tool');

        // 1. Find User
        $email = $this->ask('Enter the email of the user you want to manage');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found!');
            // Optional: fuzzy search or list users could be added here, but keeping it simple for now
            return 1;
        }

        $this->info("Selected User: {$user->name} ({$user->email})");

        // 2. Choose Action
        $action = $this->choice('What do you want to assign?', [
            'Role',
            'Permission',
        ], 'Role');

        if ($action === 'Role') {
            return $this->assignRole($user);
        } else {
            return $this->assignPermission($user);
        }
    }

    protected function assignRole(User $user)
    {
        $roles = Role::pluck('slug')->toArray();

        if (empty($roles)) {
            $this->error('No roles found in the system.');
            return 1;
        }

        $selectedRole = $this->choice('Select a Role to assign:', $roles);

        $user->assignRole($selectedRole);

        $this->info("Successfully assigned role '{$selectedRole}' to {$user->name}.");
        return 0;
    }

    protected function assignPermission(User $user)
    {
        $permissions = Permission::pluck('slug')->toArray();

        // Search functionality for permissions because there might be many
        $search = $this->ask('Search for a permission (leave empty to list all, or type a keyword like "create")');

        if ($search) {
            $filteredPermissions = array_filter($permissions, function ($p) use ($search) {
                return str_contains($p, $search);
            });

            if (empty($filteredPermissions)) {
                $this->error("No permissions found matching '{$search}'.");
                return 1;
            }

            // Re-index array for choice
            $permissions = array_values($filteredPermissions);
        }

        if (empty($permissions)) {
            $this->error('No permissions available.');
            return 1;
        }

        $selectedPermission = $this->choice('Select a Permission to assign:', $permissions);

        $user->givePermission($selectedPermission);

        $this->info("Successfully granted permission '{$selectedPermission}' to {$user->name}.");
        return 0;
    }
}
