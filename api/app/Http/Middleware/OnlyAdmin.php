<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyAdmin
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
        if (!Auth::check()) {
            return response(['error' => 'Not authenticated'], 401);
        }

        if (User::find(Auth::id())->is('admin')) {
            return $next($request);
        }

        return response(['error' => 'Forbidden, only admin'], 403);
    }
}
