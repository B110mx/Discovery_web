<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = [
            [
                'titulo' => 'Preescolar',
                'descripcion' => 'Momentos destacados de la vida escolar en Discovery.',
                'imagen_url' => 'eventos/preescolar.jpg',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'titulo' => 'Primaria',
                'descripcion' => 'Momentos destacados de la vida escolar en Discovery.',
                'imagen_url' => 'eventos/primaria.jpg',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'titulo' => 'Secundaria',
                'descripcion' => 'Momentos destacados de la vida escolar en Discovery.',
                'imagen_url' => 'eventos/secundaria.jpg',
                'orden' => 3,
                'activo' => true,
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::create($evento);
        }
    }
}

