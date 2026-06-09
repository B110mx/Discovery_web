<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactoController;

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
