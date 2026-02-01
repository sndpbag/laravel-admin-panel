<?php

namespace Sndpbag\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check Permission (Handling Super Admin & Hierarchy inside trait)
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You do not have permission: ' . $permission);
    }
}
