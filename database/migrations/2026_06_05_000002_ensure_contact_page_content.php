<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('pagina_contenidos')->updateOrInsert(
            ['slug' => 'contacto'],
            [
                'titulo' => 'Contacto',
                'subtitulo' => 'Informes y admisiones',
                'descripcion' => 'Compártenos tus datos y nos pondremos en contacto contigo para darte más información sobre Colegio Discovery®.',
                'direccion' => 'Vía Puebla #3611, Residencial Cinco Bosques, Tehuacán, Puebla C.P. 75855.',
                'telefono_principal' => '(238) 688 11 79',
                'telefono_secundario' => '(238) 102 18 17',
                'correo' => 'informes@colegio-discovery.edu.mx',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        );
    }

    public function down(): void
    {
        //
    }
};
