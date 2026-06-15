<?php

namespace Database\Seeders;

use App\Models\NivelContenido;
use Illuminate\Database\Seeder;

class NivelContenidoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('data/nivel_contenidos.php') as $slug => $content) {
            NivelContenido::query()->firstOrCreate(
                ['slug' => $slug],
                $content,
            );
        }
    }
}
