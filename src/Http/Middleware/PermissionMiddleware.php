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
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('login');
        }

        $user = Auth::user();

        // Check Permission (Handling Super Admin & Hierarchy inside trait)
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission: ' . $permission
            ], 403);
        }

        abort(403, 'Unauthorized. You do not have permission: ' . $permission);
    }
}
