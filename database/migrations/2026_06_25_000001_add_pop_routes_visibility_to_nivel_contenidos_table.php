<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nivel_contenidos', function (Blueprint $table) {
            $table->json('pop_rutas_visibles')->nullable()->after('oferta_puntos');
        });
    }

    public function down(): void
    {
        Schema::table('nivel_contenidos', function (Blueprint $table) {
            $table->dropColumn('pop_rutas_visibles');
        });
    }
};
