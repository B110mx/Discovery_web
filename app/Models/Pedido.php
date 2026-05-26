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
        return config('colegio.tienda.productos', []);
    }

    public static function nivelesDisponibles(): array
    {
        return config('colegio.tienda.niveles', []);
    }

    public static function estadosDisponibles(): array
    {
        return config('colegio.tienda.estados_pedido', []);
    }

    public function getProductosResumenAttribute(): string
    {
        return collect($this->productos ?? [])
            ->map(fn (array $producto) => "{$producto['cantidad']} x {$producto['nombre']} ($" . number_format($producto['subtotal'], 2) . ')')
            ->implode("\n");
    }
}
