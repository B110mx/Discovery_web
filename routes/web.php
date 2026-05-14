<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PedidoController;
use App\Models\PaginaContenido;
use App\Models\Evento;
use Illuminate\Support\Facades\File;



Route::get('/', function () {
    $testimoniosPath = base_path('videosyfotos/Testimonios Alumni/Testimonios Alumni');
    $mediaUrl = function ($file): string {
        $basePath = str_replace('\\', '/', realpath(base_path('videosyfotos')));
        $filePath = str_replace('\\', '/', $file->getRealPath());
        $relativePath = ltrim(substr($filePath, strlen($basePath)), '/');

        return url('/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/'));
    };

    // Obtener eventos de la base de datos
    $eventos = Evento::where('activo', true)
        ->orderBy('orden')
        ->get()
        ->map(fn ($evento) => [
            'titulo' => $evento->titulo,
            'descripcion' => $evento->descripcion ?? 'Momentos destacados de la vida escolar en Discovery.',
            'url' => asset('storage/' . $evento->imagen_url),
        ])
        ->values()
        ->all();

    $testimonios = File::isDirectory($testimoniosPath)
        ? collect(File::files($testimoniosPath))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['mp4', 'mov', 'webm', 'm4v']))
            ->map(fn ($file) => [
                'titulo' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                'url' => $mediaUrl($file),
            ])
            ->values()
            ->all()
        : [];

    return view('pages.inicio', [
        'eventos' => $eventos,
        'testimonios' => $testimonios,
    ]);
})->name('inicio');

Route::get('/media/{path}', function (string $path) {
    $basePath = str_replace('\\', '/', realpath(base_path('videosyfotos')));
    $filePath = str_replace('\\', '/', realpath(base_path('videosyfotos/' . $path)) ?: '');

    abort_unless($basePath && $filePath && ($filePath === $basePath || str_starts_with($filePath, $basePath . '/')) && is_file($filePath), 404);

    return response()->file($filePath);
})->where('path', '.*')->name('media');

Route::get('/nosotros', function () {
    return view('pages.nosotros');
})->name('nosotros');

Route::get('/oferta-academica', function () {
    return view('pages.oferta-academica');
})->name('oferta-academica');

Route::get('/protagonistas', function () {
    return view('pages.protagonistas');
})->name('protagonistas');

Route::get('/recursos-escolares', function () {
    return view('pages.recursos-escolares');
})->name('recursos-escolares');

Route::get('/tienda', [PedidoController::class, 'create'])->name('tienda');
Route::post('/tienda', [PedidoController::class, 'store'])->name('tienda.store');

Route::get('/contacto', function () {
    return view('pages.contacto', [
        'pagina' => PaginaContenido::where('slug', 'contacto')->first(),
    ]);
})->name('contacto');

Route::post('/contacto', [ContactoController::class, 'store']);

Route::get('/oferta-academica/{nivel}', function (string $nivel) {
    $niveles = [
        'preescolar' => [
            'titulo' => 'Preescolar',
            'descripcion' => 'Un entorno cercano para iniciar el aprendizaje con seguridad, creatividad y acompanamiento.',
        ],
        'primaria' => [
            'titulo' => 'Primaria',
            'descripcion' => 'Bases academicas solidas, valores y experiencias que despiertan la curiosidad.',
        ],
        'secundaria' => [
            'titulo' => 'Secundaria',
            'descripcion' => 'Formacion integral con tecnologia, proyectos y desarrollo personal.',
        ],
        'bachillerato' => [
            'titulo' => 'Bachillerato',
            'descripcion' => 'Preparacion para la universidad con orientacion vocacional y alto nivel academico.',
        ],
        'ib-en-discovery' => [
            'titulo' => 'IB en Discovery',
            'descripcion' => 'Una experiencia educativa con enfoque internacional y pensamiento critico.',
        ],
        'certificacion-de-ingles' => [
            'titulo' => 'Certificacion de Ingles',
            'descripcion' => 'Desarrollo del idioma ingles con metas claras y acompanamiento academico.',
        ],
    ];

    abort_unless(isset($niveles[$nivel]), 404);

    $carpetas = [
        'preescolar' => 'Kinder',
        'primaria' => 'Elementary (Primaria)',
        'secundaria' => 'Middle (Secundaria)',
        'bachillerato' => 'High (Prepa)',
    ];

    $galeria = [];
    $mediaUrl = function ($file): string {
        $basePath = str_replace('\\', '/', realpath(base_path('videosyfotos')));
        $filePath = str_replace('\\', '/', $file->getRealPath());
        $relativePath = ltrim(substr($filePath, strlen($basePath)), '/');

        return url('/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/'));
    };

    if (isset($carpetas[$nivel])) {
        $rutaGaleria = base_path('videosyfotos/' . $carpetas[$nivel]);

        if (File::isDirectory($rutaGaleria)) {
            $galeria = collect(File::files($rutaGaleria))
                ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
                ->take(12)
                ->map(fn ($file) => [
                    'alt' => $niveles[$nivel]['titulo'],
                    'url' => $mediaUrl($file),
                ])
                ->values()
                ->all();
        }
    }

    return view('pages.nivel', [
        'nivel' => $niveles[$nivel],
        'galeria' => $galeria,
    ]);
})->name('nivel');
