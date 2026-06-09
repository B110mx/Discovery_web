<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vistas controlables desde el dashboard
    |--------------------------------------------------------------------------
    |
    | La clave debe coincidir con el parámetro del middleware en routes/web.php.
    | "padre" hace que una vista herede el cierre de una sección completa.
    |
    */
    'vistas' => [
        'inicio' => ['nombre' => 'Inicio', 'grupo' => 'Páginas principales'],
        'nosotros' => ['nombre' => 'Conócenos', 'grupo' => 'Páginas principales'],
        'oferta-academica' => ['nombre' => 'Oferta Educativa', 'grupo' => 'Páginas principales'],
        'preescolar' => ['nombre' => 'Kinder', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'primaria' => ['nombre' => 'Elementary', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'secundaria' => ['nombre' => 'Middle', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'bachillerato' => ['nombre' => 'High', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'ib-en-discovery' => ['nombre' => 'IB en Discovery®', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'pop-del-ib' => ['nombre' => 'POP del IB', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'certificacion-de-ingles' => ['nombre' => 'Certificación de Inglés', 'grupo' => 'Oferta Educativa', 'padre' => 'oferta-academica'],
        'protagonistas' => ['nombre' => 'Comunidad / Protagonistas', 'grupo' => 'Comunidad'],
        'academias-vespertinas' => ['nombre' => 'Academias Vespertinas', 'grupo' => 'Comunidad'],
        'recursos-escolares' => ['nombre' => 'Recursos escolares', 'grupo' => 'Páginas principales'],
        'contacto' => ['nombre' => 'Contacto', 'grupo' => 'Páginas principales'],
    ],
];
