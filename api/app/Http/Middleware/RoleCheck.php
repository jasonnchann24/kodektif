<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return response(['error' => 'Not authenticated'], 401);
        }

        $roleIds = config('constants.role_ids');
        $allowedRoleIds = [];
        foreach ($roles as $role) {
            if (isset($roleIds[$role])) {
                $allowedRoleIds[] = $roleIds[$role];
            }
        }
        $allowedRoleIds = array_unique($allowedRoleIds);

        $userRoles = Auth::user()->roles;
        foreach ($userRoles as $userRole) {
            if (in_array($userRole->id, $allowedRoleIds)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Forbidden request.'], 403);
    }
}
