<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vistas controlables desde el dashboard
    |--------------------------------------------------------------------------
    |
    | La clave debe coincidir con el parámetro del middleware en routes/web.php.
    | El grupo organiza las vistas en el panel, sin compartir su publicación.
    |
    */
    'vistas' => [
        'inicio' => ['nombre' => 'Inicio', 'grupo' => 'Páginas principales'],
        'nosotros' => ['nombre' => 'Conócenos', 'grupo' => 'Páginas principales'],
        'oferta-academica' => ['nombre' => 'Oferta Educativa', 'grupo' => 'Páginas principales'],
        'preescolar' => ['nombre' => 'Kindergarten', 'grupo' => 'Oferta Educativa'],
        'primaria' => ['nombre' => 'Elementary', 'grupo' => 'Oferta Educativa'],
        'secundaria' => ['nombre' => 'Middle School', 'grupo' => 'Oferta Educativa'],
        'bachillerato' => ['nombre' => 'High School', 'grupo' => 'Oferta Educativa'],
        'ib-en-discovery' => ['nombre' => 'IB® en Discovery®', 'grupo' => 'Oferta Educativa'],
        'pop-del-ib' => ['nombre' => 'POP del IB®', 'grupo' => 'Oferta Educativa'],
        'certificacion-de-ingles' => ['nombre' => 'Certificación de Inglés', 'grupo' => 'Oferta Educativa'],
        'protagonistas' => ['nombre' => 'Comunidad / Protagonistas', 'grupo' => 'Comunidad'],
        'academias-vespertinas' => ['nombre' => 'Academias Vespertinas', 'grupo' => 'Comunidad'],
        'recursos-escolares' => ['nombre' => 'Recursos escolares', 'grupo' => 'Páginas principales'],
        'contacto' => ['nombre' => 'Contacto', 'grupo' => 'Páginas principales'],
    ],
];
