<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

/** PDF de lista de útiles agrupado por nivel, grado y ciclo escolar. */
class ListaUtil extends Model
{
    protected $table = 'lista_utiles';

    protected $fillable = [
        'ciclo_escolar',
        'nivel',
        'grado',
        'titulo',
        'archivo_pdf',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('recursos_listas_utiles'));
        static::deleted(fn () => SiteCache::forget('recursos_listas_utiles'));
    }
}
