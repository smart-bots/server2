<?php

namespace SmartBots\Http\Middleware;

use Closure;

class SslProtocol {

    /**
     * Handle an incoming request, make it secured
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->setTrustedProxies( [ $request->getClientIp() ] ); // Trust CloudFlare to avoid inf loop

        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
