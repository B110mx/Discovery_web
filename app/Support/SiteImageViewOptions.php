<?php

namespace App\Support;

class SiteImageViewOptions
{
    public static function all(): array
    {
        return [
            'inicio' => 'Inicio',
            'nosotros' => 'Nosotros',
            'oferta-academica' => 'Oferta Educativa',
            'preescolar' => 'Nivel - Kindergarten',
            'primaria' => 'Nivel - Elementary',
            'secundaria' => 'Nivel - Middle School',
            'bachillerato' => 'Nivel - High School',
            'ib-en-discovery' => 'Nivel - IB en Discovery®',
            'pop-del-ib' => 'POP del IB',
            'certificacion-de-ingles' => 'Certificación de Inglés',
            'academias-vespertinas' => 'Academias Vespertinas',
            'recursos-escolares' => 'Recursos escolares',
            'contacto' => 'Contacto',
        ];
    }
}
