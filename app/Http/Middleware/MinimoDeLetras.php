<?php

namespace App\Http\Middleware;

use Closure;

class MinimoContenido
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
        if (strlen($request->contenido) < 10) {
            return abort(403,'No cumple el minimo de letras en contenido');
        }

        return $next($request);
    }
}
