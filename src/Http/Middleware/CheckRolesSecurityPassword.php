<?php

namespace Sndpbag\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRolesSecurityPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user has verified the security password in this session
        if (!session('roles_security_verified')) {
            return redirect()->route('roles.security.check')
                ->with('intended_url', $request->fullUrl());
        }

        return $next($request);
    }
}
