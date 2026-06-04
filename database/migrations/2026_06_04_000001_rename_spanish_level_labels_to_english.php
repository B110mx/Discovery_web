<?php

use App\Support\SiteCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameLevelLabels('eventos', ['titulo', 'descripcion']);
        $this->renameLevelLabels('hito_historias', ['titulo', 'texto']);
        $this->renameLevelLabels('seccion_imagenes', ['titulo', 'referencia']);

        DB::table('lista_utiles')->where('nivel', 'Preescolar')->update(['nivel' => 'Kinder']);
        DB::table('lista_utiles')->where('nivel', 'Primaria')->update(['nivel' => 'Elementary']);
        DB::table('lista_utiles')->where('nivel', 'Secundaria')->update(['nivel' => 'Middle']);
        DB::table('lista_utiles')->where('nivel', 'Bachillerato')->update(['nivel' => 'High']);

        foreach ([
            'inicio_eventos',
            'nosotros_historia',
            'recursos_listas_utiles',
            'seccion_imagenes.nosotros',
            'seccion_imagenes.oferta-academica',
            'seccion_imagenes.protagonistas',
            'seccion_imagenes.preescolar',
            'seccion_imagenes.primaria',
            'seccion_imagenes.secundaria',
            'seccion_imagenes.bachillerato',
        ] as $key) {
            SiteCache::forget($key);
        }
    }

    public function down(): void
    {
        $this->renameLevelLabels('eventos', ['titulo', 'descripcion'], reverse: true);
        $this->renameLevelLabels('hito_historias', ['titulo', 'texto'], reverse: true);
        $this->renameLevelLabels('seccion_imagenes', ['titulo', 'referencia'], reverse: true);

        DB::table('lista_utiles')->where('nivel', 'Kinder')->update(['nivel' => 'Preescolar']);
        DB::table('lista_utiles')->where('nivel', 'Elementary')->update(['nivel' => 'Primaria']);
        DB::table('lista_utiles')->where('nivel', 'Middle')->update(['nivel' => 'Secundaria']);
        DB::table('lista_utiles')->where('nivel', 'High')->update(['nivel' => 'Bachillerato']);

        foreach ([
            'inicio_eventos',
            'nosotros_historia',
            'recursos_listas_utiles',
            'seccion_imagenes.nosotros',
            'seccion_imagenes.oferta-academica',
            'seccion_imagenes.protagonistas',
            'seccion_imagenes.preescolar',
            'seccion_imagenes.primaria',
            'seccion_imagenes.secundaria',
            'seccion_imagenes.bachillerato',
        ] as $key) {
            SiteCache::forget($key);
        }
    }

    private function renameLevelLabels(string $table, array $columns, bool $reverse = false): void
    {
        foreach ($columns as $column) {
            foreach ($this->labelMap($reverse) as $from => $to) {
                DB::table($table)
                    ->where($column, 'like', "%{$from}%")
                    ->update([$column => DB::raw("REPLACE({$column}, '{$from}', '{$to}')")]);
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function labelMap(bool $reverse): array
    {
        $map = [
            'Preescolar' => 'Kinder',
            'Primaria' => 'Elementary',
            'Secundaria' => 'Middle',
            'Bachillerato' => 'High',
        ];

        return $reverse ? array_flip($map) : $map;
    }
};
