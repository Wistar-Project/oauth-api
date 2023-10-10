<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next){
        $origin = $request->headers->get('origin');
        $ALLOWED_ORIGINS = ["http://localhost:5500", "http://127.0.0.1:5500"];
        if(!in_array($origin, $ALLOWED_ORIGINS)){
            $origin = "";
        }
        return $next($request)
            -> header('Access-Control-Allow-Origin', $origin)
            -> header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            -> header('Access-Control-Allow-Headers', 'Content-Type')
            -> header('Access-Control-Allow-Credentials', true);
    }
}