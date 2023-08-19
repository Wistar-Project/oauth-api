<?php

namespace App\Http\Middleware;

use App\Models\PersonaRol;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EsGerente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api') -> user();
        $rol = PersonaRol::find($user -> id) -> rol;
        $UNAUTHORIZED_HTTP = 401;
        if($rol != "gerente") 
            return abort($UNAUTHORIZED_HTTP, "Se debe ser gerente");
        return $next($request);
    }
}
