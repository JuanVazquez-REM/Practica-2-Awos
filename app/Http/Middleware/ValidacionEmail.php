<?php

namespace App\Http\Middleware;

use Closure;

class ValidacionEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return abort(403,'Email no valido');
        }
        return $next($request);
    }
}
