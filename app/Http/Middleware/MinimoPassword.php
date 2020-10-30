<?php

namespace App\Http\Middleware;

use Closure;

class MinimoPassword
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
        if (strlen($request->password) < 6) {
            return abort(403,'Ingresa una contraseña de mas 6 caracteres');
        }
        return $next($request);
    }
}
