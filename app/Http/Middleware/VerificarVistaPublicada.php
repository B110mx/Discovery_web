<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\VistaPublicacion;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloquea temporalmente vistas públicas sin impedir que un administrador
 * autenticado las previsualice durante el mantenimiento.
 */
class VerificarVistaPublicada
{
    public function handle(Request $request, Closure $next, ?string $clave = null): Response
    {
        $clave = $this->resolverClave($request, $clave);

        if (! $clave || ! array_key_exists($clave, config('publicacion.vistas', []))) {
            return $next($request);
        }

        // Cada interruptor controla exclusivamente su propia ruta. Los niveles
        // siguen agrupados bajo Oferta Educativa, pero no heredan su estado.
        if (VistaPublicacion::estaPublicada($clave)) {
            return $next($request);
        }

        if ($this->esAdministrador($request)) {
            View::share('vistaEnPrevisualizacion', config("publicacion.vistas.{$clave}.nombre", $clave));

            return $next($request);
        }

        return response()->view('errors.vista-mantenimiento', [
            'nombreVista' => config("publicacion.vistas.{$clave}.nombre", 'Esta sección'),
        ], 503);
    }

    private function resolverClave(Request $request, ?string $clave): ?string
    {
        // La ruta de niveles es dinámica; el middleware recibe "nivel" y debe
        // convertirlo al slug real solicitado.
        if ($clave === 'nivel') {
            return $request->route('nivel');
        }

        return $clave;
    }

    private function esAdministrador(Request $request): bool
    {
        $user = $request->user();

        return $user instanceof User && in_array($user->role, ['admin', 'super_admin'], true);
    }
}
