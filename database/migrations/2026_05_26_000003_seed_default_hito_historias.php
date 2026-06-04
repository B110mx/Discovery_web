<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('hito_historias')->exists()) {
            return;
        }

        $now = now();

        DB::table('hito_historias')->insert([
            ['anio' => '2003', 'titulo' => 'Discovery® Kinder', 'texto' => 'Nace Discovery® Kinder, el inicio de un sueño educativo porque los primeros pasos trascienden.', 'orden' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2005', 'titulo' => 'Discovery® Elementary', 'texto' => 'Inauguración de Discovery® Elementary, creciendo con pasos firmes.', 'orden' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2011', 'titulo' => 'Discovery® Middle', 'texto' => 'Se suma Discovery® Middle, ampliando horizontes.', 'orden' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2016', 'titulo' => 'Discovery® High', 'texto' => 'Llega Discovery® High, preparando grandes líderes y descubriendo su potencial.', 'orden' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educación internacional.', 'orden' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.', 'orden' => 60, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2023', 'titulo' => 'DKMUN primera edición', 'texto' => 'Realizamos nuestra primera edición DKMUN, un espacio para el liderazgo y la diplomacia.', 'orden' => 70, 'created_at' => $now, 'updated_at' => $now],
            ['anio' => '2025', 'titulo' => 'Actualmente', 'texto' => 'Seguimos escribiendo nuestra historia, creciendo y evolucionando juntos.', 'orden' => 80, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('hito_historias')
            ->whereIn('anio', ['2003', '2005', '2011', '2016', '2018', '2019', '2023', '2025'])
            ->whereNull('imagen_url')
            ->whereNull('imagen_secundaria_url')
            ->delete();
    }
};
