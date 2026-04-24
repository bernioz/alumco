<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        $user = auth()->user();

        // Si no está logueado, o su rol NO es 'admin', cortamos todo de golpe con un 403.
        if (!$user || $user->rol !== 'admin') {
            abort(403, 'Acceso Denegado: Esta zona es exclusiva para Administradores.');
        }

        // Si es admin, lo dejamos pasar felizmente.
        return $next($request);
    }
}

