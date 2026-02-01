<?php

namespace Sndpbag\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Sndpbag\AdminPanel\Models\Permission;
use Illuminate\Support\Str;

class SyncRoutesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-roles:sync-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all named routes to the permissions table automatically.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Syncing routes directly to permissions...');

        $routes = Route::getRoutes();
        $permissionsCount = 0;

        foreach ($routes as $route) {
            $routeName = $route->getName();

            // Skip routes with no name or internal routes (like ignition, debugbar)
            if (!$routeName || Str::startsWith($routeName, ['ignition.', 'debugbar.', 'sanctum.'])) {
                continue;
            }

            // Optional: Filter by 'admin' or 'dashboard' prefix if you want only admin routes
            // if (!Str::startsWith($routeName, 'admin.') && !Str::startsWith($routeName, 'dashboard.')) {
            //     continue;
            // }

            // Determine Group Name (e.g., 'users.create' -> 'users')
            $parts = explode('.', $routeName);
            $groupName = $parts[0] ?? 'general';

            // Create or Update Permission
            $permission = Permission::firstOrCreate(
                ['slug' => $routeName],
                [
                    'name' => ucwords(str_replace('.', ' ', $routeName)),
                    'group_name' => $groupName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // If it already existed, we might want to ensure group name is set check if we want to update it
            if ($permission->wasRecentlyCreated) {
                $permissionsCount++;
                $this->line("Created permission: <info>{$routeName}</info> (Group: {$groupName})");
            }
        }

        $this->info("Sync completed! {$permissionsCount} new permissions created.");

        return Command::SUCCESS;
    }
}
