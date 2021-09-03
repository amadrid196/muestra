<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class validarLogin
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
        if (
            session()->get('id_rol') == "1" || session()->get('id_rol') == "2" || session()->get('id_rol') == "3"
            || session()->get('id_rol') == "4" || session()->get('id_rol') == "5" || session()->get('id_rol') == "6"
        ) {
            return $next($request);
        }

        return redirect('/');
    }
}
