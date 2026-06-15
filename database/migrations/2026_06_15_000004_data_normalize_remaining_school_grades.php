<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('nivel_contenidos')
            ->select(['id', 'oferta_edad', 'oferta_puntos'])
            ->orderBy('id')
            ->get()
            ->each(function (object $level): void {
                $points = json_decode($level->oferta_puntos ?: '[]', true) ?: [];
                $formattedAge = $this->formatGrades($level->oferta_edad ?? '');
                $formattedPoints = array_map(
                    fn (string $point): string => $this->formatGrades($point),
                    $points,
                );

                if ($formattedAge !== $level->oferta_edad || $formattedPoints !== $points) {
                    DB::table('nivel_contenidos')
                        ->where('id', $level->id)
                        ->update([
                            'oferta_edad' => $formattedAge,
                            'oferta_puntos' => json_encode($formattedPoints, JSON_UNESCAPED_UNICODE),
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // La normalización no se revierte para no reintroducir inconsistencias.
    }

    private function formatGrades(string $text): string
    {
        $text = str_replace('º', '°', $text);
        $text = preg_replace(
            '/\b(\d{1,2})(?!°)(?=\s+(?:a|al|y)\s+\d{1,2}°?\s+grados?\b)/u',
            '$1°',
            $text,
        ) ?? $text;

        return preg_replace('/\b(\d{1,2})(?!°)(?=\s+grados?\b)/u', '$1°', $text) ?? $text;
    }
};
