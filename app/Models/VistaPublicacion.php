<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * Estado de publicación de una ruta administrable.
 *
 * La ausencia de un registro significa "publicada" para conservar compatibilidad
 * con bases instaladas antes de incorporar el sistema de mantenimiento.
 */
class VistaPublicacion extends Model
{
    protected $table = 'vistas_publicacion';

    protected $fillable = [
        'clave',
        'publicada',
        'actualizada_por',
    ];

    protected $casts = [
        'publicada' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Los cambios del dashboard deben reflejarse en la siguiente petición.
        static::saved(fn (VistaPublicacion $vista) => Cache::forget(self::cacheKey($vista->clave)));
        static::deleted(fn (VistaPublicacion $vista) => Cache::forget(self::cacheKey($vista->clave)));
    }

    public static function estaPublicada(string $clave): bool
    {
        // Diez minutos reduce consultas sin dejar estados viejos, ya que guardar
        // o eliminar el registro invalida inmediatamente esta misma clave.
        return Cache::remember(
            self::cacheKey($clave),
            now()->addMinutes(10),
            function () use ($clave): bool {
                $publicada = static::query()->where('clave', $clave)->value('publicada');

                return $publicada === null ? true : (bool) $publicada;
            },
        );
    }

    public static function cacheKey(string $clave): string
    {
        return "vista_publicacion.{$clave}";
    }

    public function actualizadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizada_por');
    }
}
