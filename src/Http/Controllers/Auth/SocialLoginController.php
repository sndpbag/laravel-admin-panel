<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Auth;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Sndpbag\AdminPanel\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login using ' . ucfirst($provider) . '. Please try again.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // User exists, log them in
            Auth::login($user);
        } else {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)), // Random password
                'email_verified_at' => now(),
            ]);

            // Assign default role (user)
            // Ensure logic matches our registration logic to prevent errors
            $roleName = 'user';
            $role = \Sndpbag\AdminPanel\Models\Role::where('slug', $roleName)->first();

            if (!$role) {
                $role = \Sndpbag\AdminPanel\Models\Role::create([
                    'name' => 'User',
                    'slug' => $roleName,
                    'description' => 'Default user role',
                ]);
            }

            if ($user && method_exists($user, 'roles')) {
                $user->roles()->attach($role->id);
            }

            Auth::login($user);
        }

        return redirect()->route('dashboard');
    }
}
