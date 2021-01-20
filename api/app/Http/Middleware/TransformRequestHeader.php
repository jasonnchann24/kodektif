<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformRequestHeader
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
        $cookie_name = 'XSRF-TOKEN';
        $token_cookie = $request->cookie($cookie_name);
        if ($token_cookie) {
            $request->headers->add(["X-XSRF-TOKEN" => $token_cookie]);
        }

        // error_log($request->headers);

        return $next($request);
    }
}
