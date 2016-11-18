<?php

namespace SmartBots\Http\Middleware;

use Closure;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class NonAuthenticated
{
    /**
     * Handle an incoming request, deternime if user is not authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($token = JWTAuth::setRequest($request)->getToken()) {
            if (JWTAuth::authenticate($token)) {
                return response()->json(['error' => 'need_to_signout'], 401); // Or 409?
            }
        }

        return $next($request);
    }
}
