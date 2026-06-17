<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('idiomas.supported', ['es' => 'Español']));
        $fallback = config('idiomas.default', 'es');
        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, $supported, true)) {
            $locale = $requested;
            $request->session()->put('locale', $locale);
        } else {
            $locale = $request->session()->get('locale', $fallback);
        }

        if (! in_array($locale, $supported, true)) {
            $locale = $fallback;
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);
        View::share('idiomaActual', $locale);

        return $next($request);
    }
}
