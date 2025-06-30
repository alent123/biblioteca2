<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el idioma de la sesión o del usuario autenticado
        $locale = session('locale');
        
        // Si no hay idioma en sesión, verificar si el usuario está autenticado
        if (!$locale && Auth::check()) {
            $locale = Auth::user()->idioma_preferencia;
        }
        
        // Si aún no hay idioma, usar el predeterminado
        if (!$locale) {
            $locale = 'es';
        }
        
        // Validar que el idioma sea válido
        if (!in_array($locale, ['es', 'en'])) {
            $locale = 'es';
        }
        
        // Establecer el idioma
        App::setLocale($locale);
        
        // Guardar en sesión si no está guardado
        if (!session('locale')) {
            session(['locale' => $locale]);
        }
        
        return $next($request);
    }
}
