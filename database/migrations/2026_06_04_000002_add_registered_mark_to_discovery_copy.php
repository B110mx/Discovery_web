<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceInColumns('pagina_contenidos', ['titulo', 'subtitulo', 'descripcion'], 'Discovery', 'Discovery®');
        $this->replaceInColumns('eventos', ['titulo', 'descripcion'], 'Discovery', 'Discovery®');
        $this->replaceInColumns('hito_historias', ['titulo', 'texto'], 'Discovery', 'Discovery®');
        $this->replaceInColumns('seccion_imagenes', ['titulo', 'referencia'], 'Discovery', 'Discovery®');
        $this->replaceInColumns('testimonio_videos', ['titulo'], 'Discovery', 'Discovery®');
        $this->replaceInColumns('lista_utiles', ['titulo'], 'Discovery', 'Discovery®');
    }

    public function down(): void
    {
        $this->replaceInColumns('pagina_contenidos', ['titulo', 'subtitulo', 'descripcion'], 'Discovery®', 'Discovery');
        $this->replaceInColumns('eventos', ['titulo', 'descripcion'], 'Discovery®', 'Discovery');
        $this->replaceInColumns('hito_historias', ['titulo', 'texto'], 'Discovery®', 'Discovery');
        $this->replaceInColumns('seccion_imagenes', ['titulo', 'referencia'], 'Discovery®', 'Discovery');
        $this->replaceInColumns('testimonio_videos', ['titulo'], 'Discovery®', 'Discovery');
        $this->replaceInColumns('lista_utiles', ['titulo'], 'Discovery®', 'Discovery');
    }

    /**
     * @param  list<string>  $columns
     */
    private function replaceInColumns(string $table, array $columns, string $search, string $replacement): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)
                ->where($column, 'like', "%{$search}%")
                ->update([
                    $column => DB::raw("REPLACE({$column}, " . DB::getPdo()->quote($search) . ', ' . DB::getPdo()->quote($replacement) . ')'),
                ]);
        }
    }
};
