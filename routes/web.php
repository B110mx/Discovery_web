<?php

use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $urls = collect([
        route('inicio'),
        route('nosotros'),
        route('oferta-academica'),
        route('protagonistas'),
        route('academias-vespertinas'),
        route('recursos-escolares'),
        route('contacto'),
    ]);

    $nivelUrls = collect(config('colegio.niveles.definiciones', []))
        ->keys()
        ->map(fn (string $nivel): string => route('nivel', $nivel));

    $xmlUrls = $urls
        ->merge($nivelUrls)
        ->unique()
        ->map(fn (string $url): string => '    <url><loc>'.htmlspecialchars($url, ENT_XML1).'</loc></url>')
        ->implode("\n");

    return response(
        "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n{$xmlUrls}\n</urlset>\n",
        200,
        ['Content-Type' => 'application/xml'],
    );
})->name('sitemap');

/*
|--------------------------------------------------------------------------
| Páginas públicas
|--------------------------------------------------------------------------
|
| El middleware consulta los interruptores del dashboard. En la ruta dinámica
| de niveles, la clave "nivel" se resuelve al slug real solicitado.
|
*/
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'inicio')->middleware('vista.publicada:inicio')->name('inicio');
    Route::get('/nosotros', 'nosotros')->middleware('vista.publicada:nosotros')->name('nosotros');
    Route::get('/oferta-academica', 'ofertaAcademica')->middleware('vista.publicada:oferta-academica')->name('oferta-academica');
    Route::get('/oferta-academica/{nivel}', 'nivel')->middleware('vista.publicada:nivel')->name('nivel');
    Route::get('/protagonistas', 'protagonistas')->middleware('vista.publicada:protagonistas')->name('protagonistas');
    Route::get('/comunidad/academias-vespertinas', 'academiasVespertinas')->middleware('vista.publicada:academias-vespertinas')->name('academias-vespertinas');
    Route::get('/recursos-escolares', 'recursosEscolares')->middleware('vista.publicada:recursos-escolares')->name('recursos-escolares');
    Route::get('/contacto', 'contacto')->middleware('vista.publicada:contacto')->name('contacto');

    // PageController valida y normaliza la ruta antes de leer videosyfotos.
    Route::get('/media/{path}', 'serveMedia')->where('path', '.*')->name('media');
});

/*
|--------------------------------------------------------------------------
| Acciones de formularios
|--------------------------------------------------------------------------
*/
Route::post('/contacto', [ContactoController::class, 'store'])
    ->middleware('vista.publicada:contacto')
    ->name('contacto.store');
