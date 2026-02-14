<?php

namespace Sndpbag\AdminPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\SiteSetting;

class CheckMaintenanceMode
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
        // Check if maintenance mode is enabled
        if (!SiteSetting::isMaintenanceMode()) {
            return $next($request);
        }

        // Allow bypass if valid token in session
        if (session('maintenance_bypass') === true) {
            return $next($request);
        }

        // Allow if IP is whitelisted
        $allowedIps = SiteSetting::getAllowedIps();
        if (in_array($request->ip(), $allowedIps)) {
            return $next($request);
        }

        // Exclude routes that should always be accessible
        $excludedRoutes = [
            'login',
            'logout',
            'register',
            'password.*',
            'maintenance.bypass',
            'dashboard*',
            'users*',
            'roles*',
            'permissions*',
            'settings*',
            'user-logs*',
        ];

        foreach ($excludedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Show maintenance page
        return response()->view('admin-panel::maintenance', [
            'message' => SiteSetting::get('maintenance_message', 'We are currently performing scheduled maintenance.'),
            'estimatedTime' => SiteSetting::get('maintenance_estimated_time'),
        ], 503);
    }
}
