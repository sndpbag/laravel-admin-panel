<?php

namespace Sndpbag\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // 1. Super Admin Bypass
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 2. Check Roles
        if ($user->hasRole($roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You need one of the following roles: ' . implode(', ', $roles));
    }
}
