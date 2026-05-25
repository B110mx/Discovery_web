<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PedidoController;

// ---------------------------------------------------------
// Rutas de Páginas Estáticas y Dinámicas (Frontend)
// ---------------------------------------------------------
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'inicio')->name('inicio');
    Route::get('/nosotros', 'nosotros')->name('nosotros');
    Route::get('/oferta-academica', 'ofertaAcademica')->name('oferta-academica');
    Route::get('/oferta-academica/{nivel}', 'nivel')->name('nivel');
    Route::get('/protagonistas', 'protagonistas')->name('protagonistas');
    Route::get('/recursos-escolares', 'recursosEscolares')->name('recursos-escolares');
    Route::get('/contacto', 'contacto')->name('contacto');
    
    // Ruta para servir multimedia segura
    Route::get('/media/{path}', 'serveMedia')->where('path', '.*')->name('media');
});

// ---------------------------------------------------------
// Rutas de Procesamiento de Formularios (Acciones)
// ---------------------------------------------------------
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

// ---------------------------------------------------------
// Rutas de Tienda y Pedidos
// ---------------------------------------------------------
Route::prefix('tienda')->group(function () {
    Route::get('/', [PedidoController::class, 'create'])->name('tienda');
    Route::post('/', [PedidoController::class, 'store'])->name('tienda.store');
});