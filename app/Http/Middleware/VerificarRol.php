<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no tiene rol, bloquear acceso y mostrar mensaje
        if (is_null($user->rol)) {
            abort(403, 'Tu cuenta aún no ha sido activada por un administrador.');
        }

        return $next($request);
    }
}
