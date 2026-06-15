<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $levelUpdates = [
            'primaria' => [
                'from_age' => '1 a 6 grado',
                'to_age' => '1° a 6° grado',
                'from_points' => ['Inglés diario', 'Francés desde 5 grado', 'Tecnología y arte'],
                'to_points' => ['Inglés diario', 'Francés desde 5° grado', 'Tecnología y arte'],
            ],
            'secundaria' => [
                'from_age' => '7 a 9 grado',
                'to_age' => '7° a 9° grado',
            ],
            'bachillerato' => [
                'from_age' => '10 a 12 grado',
                'to_age' => '10° a 12° grado',
            ],
        ];

        foreach ($levelUpdates as $slug => $values) {
            DB::table('nivel_contenidos')
                ->where('slug', $slug)
                ->where('oferta_edad', $values['from_age'])
                ->update([
                    'oferta_edad' => $values['to_age'],
                    'updated_at' => now(),
                ]);

            if (isset($values['from_points'])) {
                DB::table('nivel_contenidos')
                    ->where('slug', $slug)
                    ->where('oferta_puntos', json_encode($values['from_points'], JSON_UNESCAPED_UNICODE))
                    ->update([
                        'oferta_puntos' => json_encode($values['to_points'], JSON_UNESCAPED_UNICODE),
                        'updated_at' => now(),
                    ]);
            }
        }

        DB::table('lista_utiles')
            ->select(['id', 'grado', 'titulo'])
            ->orderBy('id')
            ->get()
            ->each(function (object $list): void {
                $grade = preg_replace('/\b(\d{1,2})(?!°)(?=\s+grado\b)/u', '$1°', str_replace('º', '°', $list->grado));
                $title = str_replace('º', '°', $list->titulo);

                if ($grade !== $list->grado || $title !== $list->titulo) {
                    DB::table('lista_utiles')
                        ->where('id', $list->id)
                        ->update([
                            'grado' => $grade,
                            'titulo' => $title,
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // La normalización visual de grados no se revierte para evitar
        // reintroducir formatos inconsistentes en contenido administrativo.
    }
};
