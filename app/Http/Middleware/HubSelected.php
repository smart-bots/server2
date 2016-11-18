<?php

namespace SmartBots\Http\Middleware;

use Closure;

class HubSelected
{
    /**
     * Handle an incoming request, determine if hub is selected?
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //
        return $next($request);
    }
}
