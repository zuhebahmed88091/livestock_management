<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pass request if admin privilege is disabled
        if (!config('settings.IS_ADMIN_PRIVILEGE_ENABLE')) {
            return $next($request);
        }

        // Pass request if super admin
        if (Auth::user()->hasRole('Admin')) {
            return $next($request);
        }

        // Get route name matching with url
        $permission = $request->route()->getName();

        // checking whether permission is allowed for logged in user
        $permission = DB::table(config('entrust.permissions_table'))->where('name', $permission)->first();
        if (!empty($permission) && Auth::user()->can($permission->name)) {
            return $next($request);
        }

        return abort('403');
    }
}
