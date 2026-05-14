<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'alumno_nombre',
        'alumno_nivel',
        'alumno_grado',
        'padre_nombre',
        'padre_telefono',
        'padre_email',
        'productos',
        'total',
        'estado',
        'notas',
    ];

    protected $casts = [
        'productos' => 'array',
        'total' => 'decimal:2',
    ];

    public static function productosDisponibles(): array
    {
        return [
            'cuaderno-profesional' => ['nombre' => 'Cuaderno profesional', 'nivel' => 'Todos los niveles', 'precio' => 45],
            'crayones-colores' => ['nombre' => 'Crayones y lapices de colores', 'nivel' => 'Preescolar y Primaria', 'precio' => 95],
            'resistol-pegamento' => ['nombre' => 'Resistol y pegamento escolar', 'nivel' => 'Preescolar y Primaria', 'precio' => 35],
            'hojas-folder' => ['nombre' => 'Hojas blancas y folder plastico', 'nivel' => 'Todos los niveles', 'precio' => 60],
            'lapices-plumas' => ['nombre' => 'Lapices y plumas', 'nivel' => 'Primaria, Secundaria y Bachillerato', 'precio' => 55],
            'tijeras' => ['nombre' => 'Tijeras escolares', 'nivel' => 'Preescolar y Primaria', 'precio' => 40],
            'juego-geometria' => ['nombre' => 'Juego de geometria', 'nivel' => 'Primaria, Secundaria y Bachillerato', 'precio' => 70],
            'diccionario' => ['nombre' => 'Diccionario escolar', 'nivel' => 'Primaria y Secundaria', 'precio' => 120],
            'carpeta-argollas' => ['nombre' => 'Carpeta de argollas', 'nivel' => 'Secundaria y Bachillerato', 'precio' => 85],
            'calculadora-cientifica' => ['nombre' => 'Calculadora cientifica', 'nivel' => 'Secundaria y Bachillerato', 'precio' => 280],
            'memoria-usb' => ['nombre' => 'Memoria USB', 'nivel' => 'Secundaria y Bachillerato', 'precio' => 140],
        ];
    }

    public function getProductosResumenAttribute(): string
    {
        return collect($this->productos ?? [])
            ->map(fn (array $producto) => "{$producto['cantidad']} x {$producto['nombre']} ($" . number_format($producto['subtotal'], 2) . ')')
            ->implode("\n");
    }
}
