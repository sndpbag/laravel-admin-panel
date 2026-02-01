<?php

namespace Sndpbag\AdminPanel\Console\Commands;

use Illuminate\Console\Command;
use Sndpbag\AdminPanel\Models\User;
use Sndpbag\AdminPanel\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-panel:make-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin user or assign the role to an existing user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Super Admin Creation Tool');

        $choice = $this->choice('Do you want to assign to an existing user or create a new one?', [
            0 => 'Existing User',
            1 => 'New User',
        ], 0);

        if ($choice === 'Existing User') {
            return $this->handleExistingUser();
        } else {
            return $this->handleNewUser();
        }
    }

    protected function handleExistingUser()
    {
        $email = $this->ask('Please enter the email address of the existing user');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found with this email.');
            if ($this->confirm('Do you want to create a new user with this email instead?')) {
                return $this->handleNewUser($email);
            }
            return 1;
        }

        $this->assignSuperAdminRole($user);
        return 0;
    }

    protected function handleNewUser($email = null)
    {
        $email = $email ?? $this->ask('Enter Email Address');

        // Validation
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists.');
            return $this->handleExistingUser();
        }

        $name = $this->ask('Enter Name');
        $password = $this->secret('Enter Password');
        $confirmPassword = $this->secret('Confirm Password');

        while ($password !== $confirmPassword) {
            $this->error('Passwords do not match. Please try again.');
            $password = $this->secret('Enter Password');
            $confirmPassword = $this->secret('Confirm Password');
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 'Active', // Ensure regular status is active
            'email_verified_at' => now(), // Auto verify for super admin
        ]);

        $this->assignSuperAdminRole($user);
        return 0;
    }

    protected function assignSuperAdminRole(User $user)
    {
        // Ensure 'super-admin' role exists
        $role = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'User with full access to the system.']
        );

        // Assign Role
        // We use check if custom assignRole exists on model, else usage standard relationship
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('super-admin');
        } else {
            // Fallback if trait not loaded properly (though it should be)
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $this->info("Success! Role 'super-admin' has been assigned to user: {$user->email}");
        $this->line("This user now has full access to the system.");
    }
}
